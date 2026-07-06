<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contractor;
use App\Services\ClientInvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Notifications\ClientVerifiedNotification;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Client::where('contractor_id', Auth::id());

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('direction', 'desc');
        if (in_array($sortBy, ['name', 'city', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $clients = $query->paginate(10)->withQueryString();

        $routes = \App\Models\ContractorRoute::where('contractor_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('route_name')
            ->get();

        return view('clients.index', compact('clients', 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $routes = \App\Models\ContractorRoute::where('contractor_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('route_name')
            ->get();

        return view('clients.create', compact('routes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ClientInvitationService $invitationService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->where(fn ($q) => $q->where('contractor_id', Auth::id())),
            ],
            'email_2' => 'nullable|email|max:255',
            'email_3' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'phone_2' => 'required|string|max:20',
            'phone_3' => 'nullable|string|max:20',
            'address' => 'required|string',
            'region' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'street' => 'nullable|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'route' => 'nullable|string|max:255',
        ]);

        $validated['contractor_id'] = Auth::id();
        
        // Fix for SQL Not Null violation: Ensure zip_code has a value
        if (empty($validated['zip_code'])) {
            $validated['zip_code'] = 'N/A';
        }
        
        // Get contractor record for email service
        $contractor = Contractor::where('user_id', Auth::id())->first();
        
        if (!$contractor) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Contractor profile not found. Please contact support.');
        }

        // Only create the portal user and send invitation email when the client is active.
        // Inactive clients should remain unlinked so they can register later using their registration number.
        $createUserAccount = $validated['status'] === 'active';
        $sendEmail = $validated['status'] === 'active';
        
        try {
            if ($sendEmail && filter_var($validated['email'], FILTER_VALIDATE_EMAIL)) {
                // Use invitation service to create client and send email
                $result = $invitationService->createClientWithInvitation(
                    $validated,
                    $contractor,
                    $createUserAccount
                );
                
                $client = $result['client'];
                $temporaryPassword = $result['password'];
                
                // Link contractor to this client
                $contractor->client_registration_number = $client->registration_number;
                $contractor->save();
                
                // Show success with password info
                return redirect()->route('contractor.clients.index')
                    ->with('success', 'Client created successfully! Invitation email sent to ' . $client->email)
                    ->with('client_password', $temporaryPassword)
                    ->with('client_email', $client->email)
                    ->with('client_registration', $client->registration_number);
            } else {
                // Create client without email invitation
                $maybeUser = User::whereRaw('LOWER(email) = ?', [strtolower($validated['email'])])->first();
                if ($maybeUser && $maybeUser->isClient() && $validated['status'] === 'active') {
                    $validated['user_id'] = $maybeUser->id;
                }
                
                $client = Client::create($validated);
                
                // Link contractor to this client
                $contractor->client_registration_number = $client->registration_number;
                $contractor->save();
                
                return redirect()->route('contractor.clients.index')
                    ->with('success', 'Client created successfully (no invitation sent).');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to create client with invitation', [
                'error' => $e->getMessage(),
                'contractor_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create client: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(404);
        }
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(404);
        }
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($client->id)->where(fn ($q) => $q->where('contractor_id', Auth::id())),
            ],
            'email_2' => 'nullable|email|max:255',
            'email_3' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'phone_2' => 'required|string|max:20',
            'phone_3' => 'nullable|string|max:20',
            'address' => 'required|string',
            'region' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'street' => 'nullable|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'notes' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive'
        ]);

        // Refresh link if email changed
        $maybeUser = User::whereRaw('LOWER(email) = ?', [strtolower($validated['email'])])->first();
        $validated['user_id'] = ($maybeUser && $maybeUser->isClient()) ? $maybeUser->id : null;

        $client->update($validated);

        return redirect()->route('contractor.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(404);
        }
        
        $client->delete();

        return redirect()->route('contractor.clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    /**
     * Resend invitation email to client
     */
    public function resendInvitation(Client $client, ClientInvitationService $invitationService)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(404);
        }

        $contractor = Contractor::where('user_id', Auth::id())->first();

        if (!$contractor) {
            return redirect()->back()
                ->with('error', 'Contractor profile not found.');
        }

        if (!$client->email) {
            return redirect()->back()
                ->with('error', 'Client does not have an email address.');
        }

        try {
            $sent = $invitationService->resendInvitation($client, $contractor);

            if ($sent) {
                return redirect()->back()
                    ->with('success', 'Invitation email resent successfully to ' . $client->email);
            }

            return redirect()->back()
                ->with('error', 'Failed to send invitation email.');
        } catch (\Exception $e) {
            \Log::error('Failed to resend invitation', [
                'error' => $e->getMessage(),
                'client_id' => $client->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to resend invitation: ' . $e->getMessage());
        }
    }

    /**
     * Reset client password and send new credentials
     */
    public function resetPassword(Client $client, ClientInvitationService $invitationService)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(404);
        }

        $contractor = Contractor::where('user_id', Auth::id())->first();
        $user = $client->user;

        if (!$user) {
            return redirect()->back()
                ->with('error', 'Client does not have a user account.');
        }

        try {
            // Generate new temporary password
            $newPassword = \Str::random(12);
            $user->password = \Hash::make($newPassword);
            $user->save();

            // Send invitation with new password
            $user->notify(new \App\Notifications\ClientInvitation($client, $contractor, $newPassword));

            return redirect()->back()
                ->with('success', 'Password reset successfully! New credentials sent to ' . $client->email)
                ->with('client_password', $newPassword)
                ->with('client_email', $client->email);
        } catch (\Exception $e) {
            \Log::error('Failed to reset client password', [
                'error' => $e->getMessage(),
                'client_id' => $client->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to reset password: ' . $e->getMessage());
        }
    }

    /**
     * Bulk approve pending self-registered clients
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
        ]);

        $contractor = Contractor::where('user_id', Auth::id())->first();
        if (!$contractor) {
            return back()->with('error', 'Contractor profile not found.');
        }

        $approvedCount = 0;
        foreach ($request->client_ids as $id) {
            $client = Client::where('id', $id)
                ->where('contractor_id', Auth::id())
                ->where('status', 'pending')
                ->first();

            if ($client) {
                // Generate a temporary password
                $tempPassword = Str::random(10);

                // Check if user already exists
                $user = User::where('email', $client->email)->first();
                if (!$user) {
                    $user = User::create([
                        'name'               => $client->name,
                        'email'              => $client->email,
                        'password'           => Hash::make($tempPassword),
                        'user_type'          => 'client',
                        'email_verified_at'  => now(),
                    ]);
                }

                // Link and activate the client
                $client->update([
                    'user_id'     => $user->id,
                    'status'      => 'active',
                    'verified_at' => now(),
                ]);

                // Send approval email with credentials
                try {
                    $user->notify(new ClientVerifiedNotification($client, $contractor, $tempPassword));
                } catch (\Exception $e) {
                    Log::error('Failed to send bulk client approval email for client ' . $client->id . ': ' . $e->getMessage());
                }

                $approvedCount++;
            }
        }

        return back()->with('success', "Successfully approved {$approvedCount} client(s).");
    }

    /**
     * Bulk assign selected clients to a route
     */
    public function bulkAssignRoute(Request $request)
    {
        $request->validate([
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
            'route' => 'required|string',
        ]);

        $updatedCount = Client::whereIn('id', $request->client_ids)
            ->where('contractor_id', Auth::id())
            ->update(['route' => $request->route]);

        return back()->with('success', "Successfully assigned {$updatedCount} client(s) to route '{$request->route}'.");
    }
}
