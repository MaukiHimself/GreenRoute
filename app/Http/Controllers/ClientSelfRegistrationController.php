<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ContractorRoute;
use App\Models\Location;
use App\Models\User;
use App\Models\Contractor;
use App\Notifications\ClientVerifiedNotification;
use App\Notifications\ClientPendingApprovalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ClientSelfRegistrationController extends Controller
{
    /**
     * Show the public self-registration form.
     */
    public function showForm()
    {
        $regions = collect([]);
        if (Schema::hasTable('tbl_locations')) {
            try {
                $regions = Location::select('region')->distinct()->orderBy('region')->pluck('region');
            } catch (\Exception $e) {
                $regions = collect([]);
            }
        }

        return view('auth.client.self-register', compact('regions'));
    }

    /**
     * Find contractors operating in a given region/route.
     * Returns JSON for the AJAX dropdown.
     */
    public function getContractorRoutes(Request $request)
    {
        $request->validate(['region' => 'required|string']);

        $routes = ContractorRoute::where('is_active', true)
            ->where('region', $request->region)
            ->with('contractor:id,name')
            ->orderBy('route_name')
            ->get(['id', 'contractor_id', 'route_name', 'region', 'district', 'ward']);

        return response()->json(['success' => true, 'data' => $routes]);
    }

    /**
     * Handle the self-registration form submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'contact_name'  => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:clients,email',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:500',
            'region'        => 'required|string|max:100',
            'district'      => 'nullable|string|max:100',
            'ward'          => 'nullable|string|max:100',
            'street'        => 'nullable|string|max:100',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'category'      => 'required|string|max:255',
            'route_id'      => 'required|exists:contractor_routes,id',
            'notes'         => 'nullable|string|max:1000',
        ], [
            'email.unique'   => 'This email is already registered.',
            'route_id.required' => 'Please select a collection route in your area.',
            'route_id.exists'   => 'Selected route is invalid.',
        ]);

        // Resolve the chosen route → contractor
        $route = ContractorRoute::with('contractor')->findOrFail($validated['route_id']);
        $contractor = Contractor::where('user_id', $route->contractor_id)->first();

        if (! $contractor) {
            return back()->withErrors(['route_id' => 'The selected route has no assigned contractor.'])->withInput();
        }

        // Create the client record as pending
        $client = Client::create([
            'contractor_id'  => $route->contractor_id,
            'name'           => $validated['name'],
            'contact_name'   => $validated['contact_name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'],
            'address'        => $validated['address'],
            'latitude'       => $validated['latitude'],
            'longitude'      => $validated['longitude'],
            'region'         => $validated['region'],
            'district'       => $validated['district'] ?? null,
            'ward'           => $validated['ward']     ?? null,
            'street'         => $validated['street']   ?? null,
            'city'           => $validated['district'] ?? $validated['region'],
            'state'          => $validated['region'],
            'zip_code'       => 'N/A',
            'category'       => $validated['category'],
            'route'          => $route->route_name,
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'pending',       // pending until contractor approves
            'self_registered' => true,
        ]);

        // Notify the contractor that a new client is awaiting approval
        try {
            $contractorUser = User::find($route->contractor_id);
            if ($contractorUser) {
                $contractorUser->notify(new ClientPendingApprovalNotification($client, $contractor));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to notify contractor of new client registration: ' . $e->getMessage());
        }

        return redirect()->route('client.registration.pending')
            ->with('success', 'Registration submitted! Your contractor will review and approve your account.');
    }

    /**
     * Show "pending approval" page after self-registration.
     */
    public function pendingPage()
    {
        return view('auth.client.registration-pending');
    }

    // ─── Contractor approval flow ────────────────────────────────────────────

    /**
     * Show list of clients pending approval (contractor only).
     */
    public function pendingApprovals()
    {
        $pending = Client::where('contractor_id', Auth::id())
            ->where('status', 'pending')
            ->where('self_registered', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('contractor.pending-clients', compact('pending'));
    }

    /**
     * Approve a self-registered client — create their user account + send credentials.
     */
    public function approve(Request $request, Client $client)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(403);
        }

        if ($client->status !== 'pending') {
            return back()->with('error', 'Client is not in pending status.');
        }

        // Generate a temporary password
        $tempPassword = Str::random(10);

        // Create the user account
        $user = User::create([
            'name'               => $client->name,
            'email'              => $client->email,
            'password'           => Hash::make($tempPassword),
            'user_type'          => 'client',
            'email_verified_at'  => now(),
        ]);

        // Link and activate the client
        $client->update([
            'user_id'     => $user->id,
            'status'      => 'active',
            'verified_at' => now(),
        ]);

        // Send approval email with credentials
        $contractor = Contractor::where('user_id', Auth::id())->first();
        try {
            $user->notify(new ClientVerifiedNotification($client, $contractor, $tempPassword));
        } catch (\Exception $e) {
            \Log::error('Failed to send client approval email: ' . $e->getMessage());
        }

        return back()
            ->with('success', "Client {$client->name} approved. Login credentials sent to {$client->email}.")
            ->with('client_password', $tempPassword)
            ->with('client_email', $client->email);
    }

    /**
     * Reject a self-registered client.
     */
    public function reject(Request $request, Client $client)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['reason' => 'nullable|string|max:500']);

        $client->update(['status' => 'inactive']);

        return back()->with('success', "Client {$client->name} has been rejected.");
    }
}
