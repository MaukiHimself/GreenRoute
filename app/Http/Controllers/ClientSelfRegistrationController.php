<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ContractorRoute;
use App\Models\Location;
use App\Models\User;
use App\Models\Contractor;
use App\Notifications\ClientVerifiedNotification;
use App\Notifications\ClientPendingApprovalNotification;
use App\Services\ContractorMatchingService;
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
            'contact_name'  => 'nullable|string|max:255',
            'email'         => 'required|email|max:255|unique:clients,email',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:500',
            'region'        => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
            'ward'          => 'nullable|string|max:100',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'category'      => 'nullable|string|max:255',
            'notes'         => 'nullable|string|max:1000',
        ], [
            'email.unique' => 'This email is already registered.',
        ]);

        // Auto-match to a contractor that covers this area (ward → district → region),
        // using distance from the client's pin as a tiebreak.
        $match = app(ContractorMatchingService::class)->match(
            $validated['ward'] ?? null,
            $validated['district'] ?? null,
            $validated['region'] ?? null,
            $validated['latitude'] ?? null,
            $validated['longitude'] ?? null,
        );

        // Create the client record as pending. When no contractor covers the area,
        // contractor_id stays null and the client surfaces in the admin queue.
        $client = Client::create([
            'contractor_id'  => $match['contractor_id'] ?? null,
            'name'           => $validated['name'],
            'contact_name'   => $validated['contact_name'] ?? $validated['name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'],
            'address'        => $validated['address'],
            'latitude'       => $validated['latitude'] ?? null,
            'longitude'      => $validated['longitude'] ?? null,
            'region'         => $validated['region'] ?? null,
            'district'       => $validated['district'] ?? null,
            'ward'           => $validated['ward'] ?? null,
            'city'           => $validated['district'] ?? $validated['region'] ?? 'Unknown',
            'state'          => $validated['region'] ?? 'Unknown',
            'zip_code'       => 'N/A',
            'category'       => $validated['category'] ?? 'Other',
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'pending',       // pending until contractor approves
            'self_registered' => true,
        ]);

        // Notify the matched contractor that a new client is awaiting approval.
        if ($match) {
            try {
                $contractorUser = User::find($match['contractor_id']);
                $contractor     = Contractor::where('user_id', $match['contractor_id'])->first();
                if ($contractorUser) {
                    $contractorUser->notify(new ClientPendingApprovalNotification($client, $contractor));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to notify contractor of new client registration: ' . $e->getMessage());
            }

            return redirect()->route('client.registration.pending')
                ->with('success', 'Registration submitted! Your contractor will review and approve your account.');
        }

        // No coverage yet — client waits for an admin to assign a contractor.
        return redirect()->route('client.registration.pending')
            ->with('success', 'Registration received! We\'re finding a waste contractor for your area and will notify you once your account is approved.');
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

        // Reuse an existing user account if one already exists for this email
        // (can happen if the client previously registered or was added manually).
        $user = User::where('email', $client->email)->first();

        if ($user) {
            // Update the existing account to ensure it's a client type and set new temp password
            $user->update([
                'name'              => $client->name,
                'user_type'         => 'client',
                'password'          => Hash::make($tempPassword),
                'email_verified_at' => now(),
            ]);
        } else {
            // Create a fresh user account
            $user = User::create([
                'name'              => $client->name,
                'email'             => $client->email,
                'password'          => Hash::make($tempPassword),
                'user_type'         => 'client',
                'email_verified_at' => now(),
            ]);
        }

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

        // Notify the client (bell) that their account is now active
        try {
            $user->notify(new \App\Notifications\GenericNotification(
                title: 'Account approved!',
                message: 'Your account with ' . ($contractor?->company_name ?? 'your contractor') . ' has been approved. Welcome to GreenRoute!',
                url: route('client.dashboard'),
                icon: 'bi-person-check',
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send bell notification to approved client: ' . $e->getMessage());
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

        // Notify the client (bell) that their registration was rejected (if they have a user account)
        if ($client->user) {
            $contractorEntity = \App\Models\Contractor::where('user_id', Auth::id())->first();
            $companyName = $contractorEntity?->company_name ?? Auth::user()->name;
            $client->user->notify(new \App\Notifications\GenericNotification(
                title: 'Registration not approved',
                message: 'Unfortunately, your registration with ' . $companyName . ' could not be approved at this time. Please contact them for more information.',
                url: url('/client/login'),
                icon: 'bi-x-circle',
            ));
        }

        return back()->with('success', "Client {$client->name} has been rejected.");
    }
}
