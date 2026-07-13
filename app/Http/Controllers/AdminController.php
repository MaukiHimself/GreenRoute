<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Contractor;
use App\Models\ContractorRoute;
use App\Models\ContractorLocation;
use App\Models\BillingRate;
use App\Models\ContractorBillingRateChange;
use App\Models\Invoice;
use App\Models\Schedule;
use App\Models\SystemFeedback;
use App\Mail\ContractorApproved;
use App\Notifications\ClientPendingApprovalNotification;
use App\Services\ContractorMatchingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = today();

        // Core system parameters
        $contractorsCount = User::where('user_type', 'contractor')->count();
        $clientsCount = Client::count();
        $activeRoutesCount = ContractorRoute::where('is_active', true)->count();

        // Get pending verifications count (contractors without approved status)
        $pendingVerifications = User::where('user_type', 'contractor')
            ->where('status', '!=', 'approved')
            ->count();

        // Financial snapshot
        $totalRevenue = Invoice::where('status', 'paid')->sum('total_amount');
        $outstandingAmount = Invoice::where('status', 'sent')->sum('remaining_balance');
        $overdueCount = Invoice::where('status', 'sent')
            ->whereDate('due_date', '<', $today)
            ->count();
        $totalInvoices = Invoice::count();

        // Operations snapshot
        $schedulesToday = Schedule::whereDate('pickup_date', $today)->count();
        $upcomingSchedulesCount = Schedule::whereDate('pickup_date', '>=', $today)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->count();
        $completedSchedules = Schedule::where('status', 'completed')->count();

        // System feedback & assignment queue
        $openFeedbackCount = SystemFeedback::where('status', 'open')->count();
        $unassignedClients = Client::whereNull('contractor_id')->count();

        // Invoice status breakdown (for the donut chart)
        $invoiceStatusCounts = [
            'paid'    => Invoice::where('status', 'paid')->count(),
            'sent'    => Invoice::where('status', 'sent')->whereDate('due_date', '>=', $today)->count(),
            'overdue' => $overdueCount,
            'draft'   => Invoice::where('status', 'draft')->count(),
        ];

        // Recent activity feeds
        $recentFeedback = SystemFeedback::with('user')->latest()->take(5)->get();
        $recentInvoices = Invoice::with('client')->latest()->take(5)->get();
        $upcomingSchedules = Schedule::with('client')
            ->whereDate('pickup_date', '>=', $today)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('pickup_date')
            ->orderBy('pickup_time')
            ->take(5)
            ->get();

        // Pending tasks
        $pendingTasks = [];

        if ($pendingVerifications > 0) {
            $pendingTasks[] = [
                'icon' => 'person-check',
                'title' => 'Verify Contractor',
                'description' => 'New contractor registrations awaiting approval',
                'count' => $pendingVerifications,
                'link' => route('admin.verification')
            ];
        }

        if ($unassignedClients > 0) {
            $pendingTasks[] = [
                'icon' => 'person-plus',
                'title' => 'Assign Clients',
                'description' => 'Self-registered clients with no contractor assigned',
                'count' => $unassignedClients,
                'link' => route('admin.clients.unassigned')
            ];
        }

        if ($openFeedbackCount > 0) {
            $pendingTasks[] = [
                'icon' => 'life-preserver',
                'title' => 'Answer Feedback',
                'description' => 'System feedback from clients and contractors awaiting a reply',
                'count' => $openFeedbackCount,
                'link' => route('admin.feedback')
            ];
        }

        if ($overdueCount > 0) {
            $pendingTasks[] = [
                'icon' => 'exclamation-triangle',
                'title' => 'Overdue Invoices',
                'description' => 'Issued invoices that are past their due date and unpaid',
                'count' => $overdueCount,
                'link' => route('admin.billing')
            ];
        }

        // Check for inactive routes that need attention
        $inactiveRoutes = ContractorRoute::where('is_active', false)->count();
        if ($inactiveRoutes > 0) {
            $pendingTasks[] = [
                'icon' => 'signpost-split',
                'title' => 'Update Route',
                'description' => 'Routes marked as inactive need review',
                'count' => $inactiveRoutes,
                'link' => route('admin.schedules')
            ];
        }

        return view('admin.dashboard', [
            'contractorsCount' => $contractorsCount,
            'clientsCount' => $clientsCount,
            'activeRoutesCount' => $activeRoutesCount,
            'pendingVerifications' => $pendingVerifications,
            'totalRevenue' => $totalRevenue,
            'outstandingAmount' => $outstandingAmount,
            'overdueCount' => $overdueCount,
            'totalInvoices' => $totalInvoices,
            'schedulesToday' => $schedulesToday,
            'upcomingSchedulesCount' => $upcomingSchedulesCount,
            'completedSchedules' => $completedSchedules,
            'openFeedbackCount' => $openFeedbackCount,
            'unassignedClients' => $unassignedClients,
            'invoiceStatusCounts' => $invoiceStatusCounts,
            'recentFeedback' => $recentFeedback,
            'recentInvoices' => $recentInvoices,
            'upcomingSchedules' => $upcomingSchedules,
            'pendingTasks' => $pendingTasks
        ]);
    }

    public function verification()
    {
        // Get contractors pending verification
        $pendingContractors = User::where('user_type', 'contractor')
            ->where('status', 'pending')
            ->with('contractor')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get recently approved contractors
        $approvedContractors = User::where('user_type', 'contractor')
            ->where('status', 'approved')
            ->with('contractor')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        // Get rejected contractors
        $rejectedContractors = User::where('user_type', 'contractor')
            ->where('status', 'rejected')
            ->with('contractor')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        // Statistics
        $stats = [
            'pending' => User::where('user_type', 'contractor')->where('status', 'pending')->count(),
            'approved' => User::where('user_type', 'contractor')->where('status', 'approved')->count(),
            'rejected' => User::where('user_type', 'contractor')->where('status', 'rejected')->count(),
            'suspended' => User::where('user_type', 'contractor')->where('status', 'suspended')->count(),
            'total' => User::where('user_type', 'contractor')->count(),
        ];

        return view('admin.verification', compact('pendingContractors', 'approvedContractors', 'rejectedContractors', 'stats'));
    }

    public function clients()
    {
        // Get all clients with their contractors
        $clients = Client::with('contractor')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get statistics
        $totalClients = Client::count();
        $residentialCount = Client::where('category', 'residential')->count();
        $commercialCount = Client::where('category', 'commercial')->count();
        $activeCount = Client::where('status', 'active')->count();

        return view('admin.clients', [
            'clients' => $clients,
            'totalClients' => $totalClients,
            'residentialCount' => $residentialCount,
            'commercialCount' => $commercialCount,
            'activeCount' => $activeCount
        ]);
    }

    /**
     * Self-registered clients with no contractor assigned (no active route covers
     * their area). Admin assigns them manually. Suggests the nearest candidate.
     */
    public function unassignedClients(ContractorMatchingService $matcher)
    {
        $clients = Client::whereNull('contractor_id')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // All approved contractors, for the manual-assign dropdown.
        $contractors = Contractor::orderBy('company_name')->get(['user_id', 'company_name', 'name', 'region', 'district']);

        // Suggest a best-guess contractor per client (may be null).
        foreach ($clients as $client) {
            $match = $matcher->match($client->ward, $client->district, $client->region, $client->latitude, $client->longitude);
            $client->suggested_contractor_id = $match['contractor_id'] ?? null;
        }

        return view('admin.clients-unassigned', compact('clients', 'contractors'));
    }

    /**
     * Manually assign an unassigned client to a contractor and notify them.
     */
    public function assignClient(Request $request, Client $client)
    {
        $validated = $request->validate([
            'contractor_id' => 'required|exists:users,id',
        ]);

        $client->update(['contractor_id' => $validated['contractor_id']]);

        try {
            $contractorUser = User::find($validated['contractor_id']);
            $contractor     = Contractor::where('user_id', $validated['contractor_id'])->first();
            if ($contractorUser && $contractor) {
                $contractorUser->notify(new ClientPendingApprovalNotification($client, $contractor));
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify contractor of manual client assignment: ' . $e->getMessage());
        }

        return back()->with('success', "Client {$client->name} assigned. The contractor can now review and approve them.");
    }

    public function billing()
    {
        // Get all invoices with client and contractor relationships
        $invoices = \App\Models\Invoice::with(['client', 'contractor'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate statistics
        $totalRevenue = \App\Models\Invoice::where('status', 'paid')->sum('total_amount');
        $pendingAmount = \App\Models\Invoice::where('status', 'pending')->sum('total_amount');
        $overdueAmount = \App\Models\Invoice::where('status', 'overdue')->sum('total_amount');
        $totalInvoices = \App\Models\Invoice::count();

        return view('admin.billing', [
            'invoices' => $invoices,
            'totalRevenue' => $totalRevenue,
            'pendingAmount' => $pendingAmount,
            'overdueAmount' => $overdueAmount,
            'totalInvoices' => $totalInvoices
        ]);
    }

    public function schedules(Request $request)
    {
        // Build query with filters
        $query = \App\Models\Schedule::with(['client', 'contractor', 'billingRate']);

        // Filter by contractor
        if ($request->filled('contractor_id')) {
            $query->where('contractor_id', $request->contractor_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->date_to);
        }

        // Filter by organic waste
        if ($request->filled('organic_waste')) {
            $query->where('includes_organic_waste', $request->organic_waste === 'yes');
        }

        // Filter by frequency
        if ($request->filled('frequency')) {
            $query->where('frequency', $request->frequency);
        }

        $schedules = $query->orderBy('scheduled_date', 'desc')
            ->orderBy('scheduled_time', 'asc')
            ->paginate(20);

        // Calculate statistics
        $totalSchedules = \App\Models\Schedule::count();
        $completedSchedules = \App\Models\Schedule::where('status', 'completed')->count();
        $pendingSchedules = \App\Models\Schedule::where('status', 'pending')->count();
        $todaySchedules = \App\Models\Schedule::whereDate('scheduled_date', today())->count();
        $organicWasteSchedules = \App\Models\Schedule::where('includes_organic_waste', true)->count();
        $upcomingSchedules = \App\Models\Schedule::where('scheduled_date', '>=', today())->where('status', '!=', 'completed')->count();

        // Get contractors for filter dropdown
        $contractors = User::where('user_type', 'contractor')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        return view('admin.schedules-management', [
            'schedules' => $schedules,
            'totalSchedules' => $totalSchedules,
            'completedSchedules' => $completedSchedules,
            'pendingSchedules' => $pendingSchedules,
            'todaySchedules' => $todaySchedules,
            'organicWasteSchedules' => $organicWasteSchedules,
            'upcomingSchedules' => $upcomingSchedules,
            'contractors' => $contractors,
            'filters' => $request->only(['contractor_id', 'status', 'date_from', 'date_to', 'organic_waste', 'frequency'])
        ]);
    }

    public function editSchedule(\App\Models\Schedule $schedule)
    {
        $contractors = User::where('user_type', 'contractor')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        $clients = Client::orderBy('name')->get();

        return view('admin.schedules-edit', compact('schedule', 'contractors', 'clients'));
    }

    public function updateSchedule(Request $request, \App\Models\Schedule $schedule)
    {
        $validated = $request->validate([
            'contractor_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:clients,id',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'service_type' => 'required|string',
            'frequency' => 'nullable|in:daily,weekly,bi-weekly,monthly',
            'includes_organic_waste' => 'boolean',
            'organic_waste_notes' => 'nullable|string',
            'status' => 'required|in:pending,scheduled,in_progress,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $validated['includes_organic_waste'] = $request->has('includes_organic_waste');

        $schedule->update($validated);

        return redirect()->route('admin.schedules')
            ->with('success', 'Schedule updated successfully.');
    }

    public function users(Request $request)
    {
        // Build query with filters
        $query = User::query();

        // Filter by user type
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate statistics
        $totalUsers = User::count();
        $adminCount = User::where('user_type', 'admin')->count();
        $contractorCount = User::where('user_type', 'contractor')->count();
        $clientCount = User::where('user_type', 'client')->count();
        $approvedCount = User::where('status', 'approved')->count();
        $pendingCount = User::where('status', 'pending')->orWhereNull('status')->count();

        return view('admin.users-management', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'adminCount' => $adminCount,
            'contractorCount' => $contractorCount,
            'clientCount' => $clientCount,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'filters' => $request->only(['user_type', 'status', 'search'])
        ]);
    }

    public function editUser(User $user)
    {
        return view('admin.users-edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $wasApprovedContractor = $user->user_type === 'contractor' && $user->status === 'approved';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'user_type' => 'required|in:admin,contractor,client',
            'status' => 'nullable|in:pending,approved,rejected',
            'subscription_status' => 'nullable|in:active,inactive,expired'
        ]);

        $user->update($validated);

        $isNowApprovedContractor = $user->user_type === 'contractor' && $user->status === 'approved';
        if (! $wasApprovedContractor && $isNowApprovedContractor) {
            $this->sendContractorApprovalEmail($user);
        }

        return redirect()->route('admin.users')
            ->with('success', "User {$user->name} has been updated successfully.");
    }

    public function deleteUser(User $user)
    {
        // Prevent deleting current admin
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', "User {$userName} has been deleted successfully.");
    }

    public function getContractorLocations()
    {
        $contractors = User::where('user_type', 'contractor')
            ->with(['contractorLocations' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->filter(function ($contractor) {
                return $contractor->contractorLocations->isNotEmpty();
            })
            ->map(function ($contractor) {
                $location = $contractor->contractorLocations->first();
                return [
                    'id' => $contractor->id,
                    'name' => $contractor->name,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'updated_at' => $location->created_at
                ];
            });

        return response()->json($contractors->values());
    }

    public function approveContractor(User $user)
    {
        // Approve without changing the password they chose at registration
        $user->update(['status' => 'approved']);

        // Send approval email notification (contractor keeps their registration password)
        $this->sendContractorApprovalEmail($user);

        return redirect()->route('admin.verification')
            ->with('success', "Contractor {$user->name} has been approved successfully. They can log in with the password they set during registration.");
    }

    public function rejectContractor(User $user)
    {
        // Update user status to rejected
        $user->update(['status' => 'rejected']);

        // Send rejection email notification
        try {
            \Mail::to($user->email)->send(new \App\Mail\ContractorRejected($user));
        } catch (\Exception $e) {
            // Log the error but don't fail the rejection
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        return redirect()->route('admin.verification')
            ->with('success', "Contractor {$user->name} has been rejected. A notification email has been sent.");
    }

    /**
     * Send contractor approval email without interrupting admin workflow on mail failure.
     */
    private function sendContractorApprovalEmail(User $contractor, ?string $temporaryPassword = null): void
    {
        try {
            Mail::to($contractor->email)->send(new ContractorApproved($contractor, $temporaryPassword));
        } catch (\Exception $e) {
            Log::error('Failed to send approval email', [
                'contractor_id' => $contractor->id,
                'contractor_email' => $contractor->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    // Client Management Methods

    public function createClient()
    {
        $contractors = User::where('user_type', 'contractor')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        return view('admin.clients-create', compact('contractors'));
    }

    public function storeClient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'category' => 'required|in:residential,commercial',
            'contractor_id' => 'nullable|exists:users,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'service_frequency' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if (empty($request->latitude) || empty($request->longitude)) {
            return back()->withErrors([
                'location' => 'GPS location is required. Please click "Get My Location" to capture precise coordinates before creating the client.'
            ])->withInput();
        }

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;

        if ($lat < -11.7 || $lat > -0.95 || $lng < 29.3 || $lng > 40.5) {
            return back()->withErrors([
                'location' => 'The detected location does not appear to be in Tanzania. Please ensure location services are enabled and try again.'
            ])->withInput();
        }

        $validated['registration_number'] = 'CL-' . strtoupper(substr(uniqid(), -8));
        $validated['status'] = 'active';

        $client = Client::create($validated);

        return redirect()->route('admin.clients')
            ->with('success', "Client {$client->name} has been successfully registered.");
    }

    public function editClient(Client $client)
    {
        $contractors = User::where('user_type', 'contractor')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        return view('admin.clients-edit', compact('client', 'contractors'));
    }

    public function updateClient(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'category' => 'required|in:residential,commercial',
            'status' => 'required|in:active,inactive',
            'contractor_id' => 'nullable|exists:users,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'service_frequency' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if (empty($request->latitude) || empty($request->longitude)) {
            return back()->withErrors([
                'location' => 'GPS location is required. Please ensure valid coordinates are entered.'
            ])->withInput();
        }

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;

        if ($lat < -11.7 || $lat > -0.95 || $lng < 29.3 || $lng > 40.5) {
            return back()->withErrors([
                'location' => 'The location does not appear to be in Tanzania. Please verify the coordinates.'
            ])->withInput();
        }

        $client->update($validated);

        return redirect()->route('admin.clients')
            ->with('success', "Client {$client->name} has been successfully updated.");
    }

    public function deleteClient(Client $client)
    {
        $clientName = $client->name;
        $client->delete();

        return redirect()->route('admin.clients')
            ->with('success', "Client {$clientName} has been successfully deleted.");
    }

    // SMS Campaign Methods

    public function smsCampaign()
    {
        $clients = Client::whereNotNull('phone')
            ->orderBy('name')
            ->get();

        $contractors = User::where('user_type', 'contractor')
            ->where('status', 'approved')
            ->has('clients')
            ->withCount('clients')
            ->orderBy('name')
            ->get();

        return view('admin.sms-campaign', compact('clients', 'contractors'));
    }

    public function sendSmsCampaign(Request $request)
    {
        $validated = $request->validate([
            'recipients' => 'required|in:all,residential,commercial,contractor,selected',
            'selected_clients' => 'required_if:recipients,selected|array',
            'selected_clients.*' => 'exists:clients,id',
            'contractor_id' => 'required_if:recipients,contractor|exists:users,id',
            'message' => 'required|string|max:500',
            'campaign_name' => 'required|string|max:255'
        ]);

        $recipients = $this->getSmsCampaignRecipients($request);

        $successCount = 0;
        $failCount = 0;

        foreach ($recipients as $client) {
            try {
                $this->sendSms($client->phone, $validated['message']);
                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
                \Log::error("Failed to send SMS to {$client->phone}: " . $e->getMessage());
            }
        }

        \Log::info("SMS Campaign '{$validated['campaign_name']}': {$successCount} sent, {$failCount} failed");

        return redirect()->route('admin.sms.campaign')
            ->with('success', "SMS Campaign sent successfully! {$successCount} messages sent, {$failCount} failed.");
    }

    private function getSmsCampaignRecipients(Request $request)
    {
        switch ($request->recipients) {
            case 'all':
                return Client::whereNotNull('phone')->get();
            case 'residential':
                return Client::where('category', 'residential')->whereNotNull('phone')->get();
            case 'commercial':
                return Client::where('category', 'commercial')->whereNotNull('phone')->get();
            case 'contractor':
                return Client::where('contractor_id', $request->contractor_id)->whereNotNull('phone')->get();
            case 'selected':
                return Client::whereIn('id', $request->selected_clients)->whereNotNull('phone')->get();
            default:
                return collect();
        }
    }

    private function sendSms($phone, $message)
    {
        // Placeholder for SMS integration
        // TODO: Integrate with SMS service provider (Twilio, Africa's Talking, etc.)

        // Example for Africa's Talking (popular in Africa):
        /*
        $gateway = new \AfricasTalking\SMS\SMS(config('services.africastalking.username'), config('services.africastalking.api_key'));
        $result = $gateway->send([
            'to' => $phone,
            'message' => $message,
            'from' => config('services.africastalking.shortcode')
        ]);
        */

        \Log::info("SMS to {$phone}: {$message}");

        return true;
    }

    // Billing Rates Management Methods

    public function billingRates()
    {
        $rates = BillingRate::orderBy('category')
            ->orderBy('location')
            ->orderBy('frequency')
            ->get();

        $categories = BillingRate::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $locations = BillingRate::select('location')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        $totalRates = BillingRate::count();
        $activeRates = BillingRate::where('is_active', true)->count();
        $residentialRates = BillingRate::where('category', 'LIKE', 'Residential%')->count();
        $commercialRates = BillingRate::where('category', 'LIKE', 'Commercial%')->count();

        return view('admin.billing-rates', compact('rates', 'categories', 'locations', 'totalRates', 'activeRates', 'residentialRates', 'commercialRates'));
    }

    public function billingRateChanges(Request $request)
    {
        $query = ContractorBillingRateChange::with([
            'contractor',
            'client',
            'schedule',
            'billingRate',
            'oldBillingRate',
            'newBillingRate',
        ]);

        if ($request->filled('contractor_id')) {
            $query->where('contractor_id', $request->contractor_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $changes = $query->orderByDesc('created_at')->paginate(30);

        $contractors = User::where('user_type', 'contractor')
            ->orderBy('name')
            ->get();

        return view('admin.billing-rate-changes', [
            'changes' => $changes,
            'contractors' => $contractors,
            'filters' => $request->only(['contractor_id', 'action', 'date_from', 'date_to']),
        ]);
    }

    public function createBillingRate()
    {
        return view('admin.billing-rates-create');
    }

    public function storeBillingRate(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'collection_fee' => 'required|numeric|min:0',
            'frequency' => 'nullable|in:daily,weekly,bi-weekly,monthly,per-trip',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            BillingRate::create($validated);

            return redirect()->route('admin.billing.rates')
                ->with('success', 'Billing rate created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: This category-location-frequency combination already exists.');
        }
    }

    public function editBillingRate(BillingRate $rate)
    {
        return view('admin.billing-rates-edit', compact('rate'));
    }

    public function updateBillingRate(Request $request, BillingRate $rate)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'collection_fee' => 'required|numeric|min:0',
            'frequency' => 'nullable|in:daily,weekly,bi-weekly,monthly,per-trip',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $rate->update($validated);

            return redirect()->route('admin.billing.rates')
                ->with('success', 'Billing rate updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: This category-location-frequency combination already exists.');
        }
    }

    public function deleteBillingRate(BillingRate $rate)
    {
        $rate->delete();

        return redirect()->route('admin.billing.rates')
            ->with('success', 'Billing rate deleted successfully.');
    }

    // Contractor Verification & Management Methods

    public function showContractor(User $user)
    {
        if ($user->user_type !== 'contractor') {
            abort(404);
        }

        $user->load('contractor');

        return view('admin.contractor-details', compact('user'));
    }

    public function toggleContractorStatus(User $user)
    {
        if ($user->user_type !== 'contractor') {
            abort(404);
        }

        if ($user->status === 'approved') {
            $user->update(['status' => 'suspended']);
            $message = "Contractor {$user->name} has been suspended.";

            // Notify the contractor (bell) of suspension
            $user->notify(new \App\Notifications\GenericNotification(
                title: 'Account suspended',
                message: 'Your GreenRoute contractor account has been suspended. Please contact support for more information.',
                url: url('/contractor/pending'),
                icon: 'bi-slash-circle',
            ));
        } elseif ($user->status === 'suspended') {
            $user->update(['status' => 'approved']);
            $message = "Contractor {$user->name} has been reactivated.";

            // Notify the contractor (bell) of reactivation
            $user->notify(new \App\Notifications\GenericNotification(
                title: 'Account reactivated',
                message: 'Your GreenRoute contractor account has been reactivated. You can log in and resume operations.',
                url: route('dashboard.contractor'),
                icon: 'bi-check-circle',
            ));
        } else {
            return back()->with('error', 'Cannot change status of pending or rejected contractors.');
        }

        return redirect()->route('admin.verification')
            ->with('success', $message);
    }
}
