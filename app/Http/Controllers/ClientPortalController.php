<?php

namespace App\Http\Controllers;

use App\Models\BillingRate;
use App\Models\Client;
use App\Models\EquipmentRequest;
use App\Models\Invoice;
use App\Models\Schedule;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Product;
use App\Models\Message;
use App\Notifications\GenericNotification;
use App\Support\Portal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function __construct()
    {
        // Only require auth for client portal (no email verification needed for invited clients)
        $this->middleware(['auth']);
    }

    protected function resolveClient(): ?Client
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $client = Client::where('user_id', $user->id)->first();
        if ($client) {
            return $client;
        }
        $email = strtolower($user->email);
        return Client::whereRaw('LOWER(email) = ?', [$email])->first();
    }

    public function dashboard()
    {
        $client = $this->resolveClient();

        if (!$client) {
            // If logged in as Contractor, redirect to Contractor Dashboard
            if (Auth::user()->isContractor()) {
                return redirect()->route('dashboard.contractor');
            }

            // If just a regular user with no client record
            return redirect()->route('dashboard')->with('error', 'No client account associated with this user.');
        }

        if (is_object($client) && isset($client->id) && isset($client->contractor_id)) {
            // Only show data from assigned contractor
            $contractorId = $client->contractor_id;

            // Upcoming schedules from contractor
            $upcomingSchedules = Schedule::with('contractor')
                ->where('client_id', $client->id)
                ->where('contractor_id', $contractorId)
                ->where('pickup_date', '>=', now())
                ->orderBy('pickup_date')
                ->limit(5)
                ->get();

            // All schedules from contractor for statistics
            $allSchedules = Schedule::where('client_id', $client->id)
                ->where('contractor_id', $contractorId)
                ->get();
            $missedPickups = $allSchedules->where('status', 'cancelled')->count();
            $completedPickups = $allSchedules->where('status', 'completed')->count();
            $upcomingSchedulesCount = Schedule::where('client_id', $client->id)
                ->where('contractor_id', $contractorId)
                ->where('pickup_date', '>=', now()->startOfDay())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count();

            // Invoice data from contractor only
            $allInvoices = Invoice::where('client_id', $client->id)
                ->where('contractor_id', $contractorId)
                ->get();
            $pendingInvoices = $allInvoices->whereIn('status', ['draft', 'sent', 'overdue']);
            $paidInvoices = $allInvoices->where('status', 'paid');
            $recentInvoices = $allInvoices->sortByDesc('invoice_date')->take(5);

            // Monthly payments from contractor (last 12 months)
            // Use PHP grouping to avoid SQL dialect issues (SQLite vs Postgres)
            $monthlyPayments = Invoice::where('client_id', $client->id)
                ->where('contractor_id', $contractorId)
                ->where('status', 'paid')
                ->where('paid_at', '>=', now()->subMonths(12))
                ->get()
                ->groupBy(function($invoice) {
                    return $invoice->paid_at ? $invoice->paid_at->format('Y-m') : 'unknown';
                })
                ->map(function($invoices, $key) {
                    if ($key === 'unknown') return null;

                    $parts = explode('-', $key);
                    return (object)[
                        'year' => $parts[0],
                        'month' => $parts[1],
                        'total' => $invoices->sum('total_amount')
                    ];
                })
                ->filter()
                ->sortByDesc(function($item) {
                    return $item->year . sprintf('%02d', $item->month);
                })
                ->values();

            // Feedback between client and contractor
            $recentFeedback = Feedback::where('client_id', $client->id)
                ->where('contractor_id', $contractorId)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            // Payment statistics from contractor
            $totalPaid = $paidInvoices->sum('total_amount');
            $totalPending = $pendingInvoices->sum('remaining_balance');
            $recentActivities = $this->buildRecentActivities($client, $contractorId);
        } else {
            // No contractor assigned or no client data
            $upcomingSchedules = collect();
            $allSchedules = collect();
            $missedPickups = 0;
            $completedPickups = 0;
            $allInvoices = collect();
            $pendingInvoices = collect();
            $paidInvoices = collect();
            $recentInvoices = collect();
            $monthlyPayments = collect();
            $recentFeedback = collect();
            $totalPaid = 0;
            $totalPending = 0;
            $upcomingSchedulesCount = 0;
            $recentActivities = collect();
        }

        return view('dashboards.client', [
            'client' => $client,
            'authUser' => Auth::user(),
            'upcomingSchedules' => $upcomingSchedules,
            'upcomingSchedulesCount' => $upcomingSchedulesCount ?? 0,
            'recentInvoices' => $recentInvoices,
            'allSchedules' => $allSchedules,
            'completedPickups' => $completedPickups,
            'pendingInvoices' => $pendingInvoices,
            'paidInvoices' => $paidInvoices,
            'monthlyPayments' => $monthlyPayments,
            'recentFeedback' => $recentFeedback,
            'totalPaid' => $totalPaid,
            'totalPending' => $totalPending,
            'recentActivities' => $recentActivities,
        ]);
    }

    protected function buildRecentActivities(Client $client, ?int $contractorId): Collection
    {
        $activities = collect();

        $scheduleQuery = Schedule::where('client_id', $client->id);
        if ($contractorId) {
            $scheduleQuery->where('contractor_id', $contractorId);
        }

        foreach ($scheduleQuery->orderByDesc('updated_at')->limit(5)->get() as $schedule) {
            $activities->push([
                'icon' => $schedule->status === 'completed' ? 'check-circle' : 'calendar-event',
                'color' => $schedule->status === 'completed' ? 'success' : 'primary',
                'title' => $schedule->status === 'completed' ? 'Pickup Completed' : 'Schedule ' . ucfirst(str_replace('_', ' ', $schedule->status)),
                'description' => ucfirst($schedule->service_type ?? 'collection') . ' · ' . ($schedule->pickup_location ?? 'Your location'),
                'time' => $schedule->updated_at,
            ]);
        }

        $invoiceQuery = Invoice::where('client_id', $client->id);
        if ($contractorId) {
            $invoiceQuery->where('contractor_id', $contractorId);
        }

        foreach ($invoiceQuery->orderByDesc('created_at')->limit(5)->get() as $invoice) {
            $activities->push([
                'icon' => $invoice->status === 'paid' ? 'cash-coin' : 'receipt',
                'color' => $invoice->status === 'paid' ? 'success' : 'warning',
                'title' => $invoice->status === 'paid' ? 'Payment Recorded' : 'Invoice Issued',
                'description' => ($invoice->invoice_number ?? 'Invoice') . ' · TZS ' . number_format($invoice->total_amount, 0),
                'time' => $invoice->paid_at ?? $invoice->created_at,
            ]);
        }

        if ($contractorId) {
            foreach (Message::where('client_id', $client->id)
                ->where('contractor_id', $contractorId)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get() as $message) {
                $activities->push([
                    'icon' => 'chat-dots',
                    'color' => 'info',
                    'title' => $message->sender_type === 'contractor' ? 'Message from Contractor' : 'Message Sent',
                    'description' => \Illuminate\Support\Str::limit($message->message ?? $message->content ?? 'New message', 60),
                    'time' => $message->created_at,
                ]);
            }
        }

        return $activities
            ->filter(fn ($item) => $item['time'])
            ->sortByDesc('time')
            ->take(6)
            ->values();
    }

    public function profile()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);
        return view('client_portal.profile', compact('client'));
    }

    public function updateProfile(Request $request)
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'phone_3' => 'nullable|string|max:20',
            'email_2' => 'nullable|email|max:255',
            'email_3' => 'nullable|email|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $client->update($validated);
        return redirect()->route('client.profile')->with('success', 'Profile updated successfully.');
    }

    public function schedules()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $schedules = Schedule::with(['contractor', 'billingRate'])
            ->where('client_id', $client->id)
            ->where('contractor_id', $client->contractor_id)
            ->orderByDesc('pickup_date')
            ->paginate(15);

        return view('client_portal.schedules', compact('client', 'schedules'));
    }

    public function requestService()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $products = Product::all();

        $servicePrices = collect();
        if ($client->contractor_id) {
            $servicePrices = \App\Models\ServicePrice::where('contractor_id', $client->contractor_id)
                ->where('is_active', true)
                ->orderBy('service_type')
                ->orderBy('volume_tier')
                ->get();
        }

        return view('client_portal.request_service', compact('client', 'products', 'servicePrices'));
    }

    public function storeServiceRequest(Request $request)
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        if (!$client->contractor_id) {
            return redirect()->route('client.request.service')
                ->with('error', 'No contractor is assigned to your account. Please contact support.');
        }

        $validated = $request->validate([
            'service_type' => 'required|string',
            'pickup_date' => 'required|date|after:today',
            'pickup_time' => 'required|string',
            'waste_type' => 'required|string',
            'estimated_volume' => 'required|string',
            'special_instructions' => 'nullable|string|max:1000',
        ]);

        $pickupTime = preg_match('/^(\d{2}:\d{2})/', $validated['pickup_time'], $matches)
            ? $matches[1]
            : '08:00';

        $dbServiceType = $validated['service_type'] === 'hazardous_waste' ? 'disposal' : 'collection';

        $notes = collect([
            'Requested service: ' . str_replace('_', ' ', $validated['service_type']),
            'Waste type: ' . str_replace('_', ' ', $validated['waste_type']),
            'Estimated volume: ' . str_replace('_', ' ', $validated['estimated_volume']),
            'Preferred time slot: ' . $validated['pickup_time'],
            $validated['special_instructions'] ? 'Instructions: ' . $validated['special_instructions'] : null,
        ])->filter()->implode("\n");

        $pickupLocation = $client->site_location
            ?: trim(collect([$client->ward, $client->district, $client->region])->filter()->join(', '))
            ?: ($client->city ?: 'Client site');

        $contractor = User::with('contractor')->find($client->contractor_id);

        $autoBillingRate = null;
        if ($client->category) {
            $searchLocation = $client->region ?: $client->city;
            $autoBillingRate = BillingRate::getRateByLocation($client->category, $searchLocation);
        }

        $schedulePrice = $autoBillingRate?->collection_fee;

        Schedule::create([
            'client_id' => $client->id,
            'contractor_id' => $client->contractor_id,
            'client_registration_number' => $client->registration_number,
            'contractor_registration_number' => $contractor?->contractor?->registration_number,
            'route' => $client->route ?? 'Not Assigned',
            'billing_rate_id' => $autoBillingRate?->id,
            'billing_rate_category' => $autoBillingRate?->category,
            'billing_rate_location' => $autoBillingRate?->location,
            'billing_rate_frequency' => $autoBillingRate?->frequency,
            'base_collection_fee' => $autoBillingRate?->collection_fee,
            'schedule_price' => $schedulePrice,
            'billing_rate_modified_at' => $autoBillingRate ? now() : null,
            'pickup_date' => $validated['pickup_date'],
            'pickup_time' => $pickupTime,
            'scheduled_date' => $validated['pickup_date'],
            'scheduled_time' => $pickupTime,
            'pickup_location' => $pickupLocation,
            'pickup_address' => $client->address ?? 'Not provided',
            'city' => $client->city ?? 'N/A',
            'state' => $client->state ?? 'N/A',
            'zip_code' => $client->zip_code ?? '00000',
            'service_type' => $dbServiceType,
            'status' => 'scheduled',
            'notes' => $notes,
            'includes_organic_waste' => $validated['waste_type'] === 'organic',
        ]);

        // Notify the assigned contractor (bell).
        if ($contractor) {
            $contractor->notify(new GenericNotification(
                title: 'New service request',
                message: ($client->name ?? 'A client') . ' requested a ' . str_replace('_', ' ', $validated['service_type']),
                url: route('dashboard.contractor'),
                icon: 'bi-truck',
            ));
        }

        return redirect()->route('client.schedules')->with('success', 'Service request submitted successfully.');
    }

    public function equipment()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $products = Product::where('contractor_id', $client->contractor_id)
            ->where('is_available', true)
            ->orderByDesc('created_at')
            ->get();

        // Only pending requests block re-requesting (awaiting contractor response).
        $pendingIds = EquipmentRequest::where('client_id', $client->id)
            ->where('status', 'pending')
            ->pluck('product_id')
            ->toArray();

        // Approved requests are shown so the client can request the equipment again.
        $approvedIds = EquipmentRequest::where('client_id', $client->id)
            ->where('status', 'approved')
            ->pluck('product_id')
            ->toArray();

        return view('client_portal.equipment', compact('client', 'products', 'pendingIds', 'approvedIds'));
    }

    public function requestEquipment(Request $request, Product $product)
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        // Make sure the product belongs to this client's contractor
        abort_unless((int) $product->contractor_id === (int) $client->contractor_id, 403);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
            'notes'    => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate requests only while one is still pending a response.
        // Once a request is approved (or rejected), the client may request again.
        $exists = EquipmentRequest::where('client_id', $client->id)
            ->where('product_id', $product->id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already have a pending request for this equipment awaiting a response.');
        }

        EquipmentRequest::create([
            'product_id'    => $product->id,
            'client_id'     => $client->id,
            'contractor_id' => $client->contractor_id,
            'quantity'      => $data['quantity'],
            'notes'         => $data['notes'] ?? null,
            'status'        => 'pending',
        ]);

        // Notify the contractor (bell) of the equipment request.
        $contractor = User::find($client->contractor_id);
        if ($contractor) {
            $contractor->notify(new GenericNotification(
                title: 'Equipment request',
                message: ($client->name ?? 'A client') . ' requested ' . $data['quantity'] . ' × ' . $product->name,
                url: route('contractor.equipment.requests'),
                icon: 'bi-box-seam',
            ));
        }

        return back()->with('success', 'Equipment request sent to your contractor.');
    }

    public function contractorInfo()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $contractor = User::with('contractor')->find($client->contractor_id);
        return view('client_portal.contractor_info', compact('client', 'contractor'));
    }

    public function invoices()
    {
        $client = $this->resolveClient();
        if (!$client) {
            $client = Client::where('registration_number', 'CL041204')->first();
        }
        abort_unless($client, 404);

        $query = Invoice::with(['contractor'])
            ->where('client_id', $client->id);

        // Only filter by contractor_id if it's set
        if ($client->contractor_id) {
            $query->where('contractor_id', $client->contractor_id);
        }

        $invoices = $query->orderByDesc('invoice_date')->paginate(15);

        return view('client_portal.invoices', compact('client', 'invoices'));
    }

    public function payments()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $query = Invoice::where('client_id', $client->id)
            ->where('status', 'paid');

        // Only filter by contractor_id if it's set
        if ($client->contractor_id) {
            $query->where('contractor_id', $client->contractor_id);
        }

        $payments = $query->orderByDesc('paid_at')->paginate(15);

        return view('client_portal.payments', compact('client', 'payments'));
    }

    public function feedback()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $feedbacks = Feedback::where('client_id', $client->id)
            ->where('contractor_id', $client->contractor_id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client_portal.feedback', compact('client', 'feedbacks'));
    }

    public function storeFeedback(Request $request)
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        Feedback::create([
            'client_id' => $client->id,
            'contractor_id' => $client->contractor_id,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        // Notify the contractor (bell) of new feedback.
        $contractor = User::find($client->contractor_id);
        if ($contractor) {
            $contractor->notify(new GenericNotification(
                title: 'New feedback',
                message: ($client->name ?? 'A client') . ': ' . $data['subject'],
                url: route('contractor.feedback.index'),
                icon: 'bi-chat-left-text',
            ));
        }

        return redirect()->route('client.feedback')->with('success', 'Feedback submitted successfully.');
    }

    public function chats()
    {
        $clientRecord = $this->resolveClient();
        abort_unless($clientRecord, 404, 'Client not found');

        // Get contractor info
        $contractor = null;
        if ($clientRecord->contractor_id) {
            $contractor = User::find($clientRecord->contractor_id);
        }

        // Get all messages between client and contractor
        $messages = collect();
        if ($contractor) {
            $messages = Message::where('contractor_id', $contractor->id)
                ->where('client_id', $clientRecord->id)
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark contractor's messages as read
            Message::where('contractor_id', $contractor->id)
                ->where('client_id', $clientRecord->id)
                ->where('sender_type', 'contractor')
                ->where('status', '!=', 'read')
                ->update(['status' => 'read', 'read_at' => now()]);
        }

        return view('client_portal.chats-standalone', compact('clientRecord', 'contractor', 'messages'));
    }

    public function support()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        return view('client_portal.support', compact('client'));
    }

    public function storeSupport(Request $request)
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        if (!$client->contractor_id) {
            return redirect()->route('client.support')
                ->with('error', 'No contractor assigned. Please contact platform support.');
        }

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        Feedback::create([
            'client_id' => $client->id,
            'contractor_id' => $client->contractor_id,
            'subject' => '[Support] ' . $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        return redirect()->route('client.support')->with('success', 'Support ticket submitted successfully.');
    }

    public function location()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        // Get route information if client is assigned to a route
        $routeClients = collect();
        $contractorRoute = null;

        if ($client->route && $client->contractor_id) {
            $contractorRoute = \App\Models\ContractorRoute::where('contractor_id', $client->contractor_id)
                ->where('route_name', $client->route)
                ->first();

            if ($contractorRoute) {
                $routeClients = Client::where('contractor_id', $client->contractor_id)
                    ->where('route', $client->route)
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->select('id', 'name', 'latitude', 'longitude', 'address', 'city', 'phone')
                    ->get();
            }
        }

        return view('client_portal.location', compact('client', 'routeClients', 'contractorRoute'));
    }

    public function updateLocation(Request $request)
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $validated = $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $client->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'GPS location updated successfully.'
            ]);
        }

        return redirect()->route('client.location')->with('success', 'GPS location updated successfully. Your contractor can now use this for route optimization and scheduling.');
    }
}
