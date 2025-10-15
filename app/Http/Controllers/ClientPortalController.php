<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Schedule;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Product;
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
        
        // For public route, use real client data for demonstration
        if (!$client) {
            $client = Client::where('registration_number', 'CL041204')->first();
            if (!$client) {
                $client = (object) [
                    'name' => 'Demo Client',
                    'contact_name' => 'John Doe',
                    'registration_number' => 'CL000000',
                    'phone' => '+1234567890',
                    'address' => '123 Demo Street',
                    'city' => 'Demo City',
                    'state' => 'Demo State',
                    'zip_code' => '12345',
                    'email' => 'demo@example.com',
                    'phone_2' => null,
                    'phone_3' => null,
                    'email_2' => null,
                    'category' => 'residential',
                    'status' => 'active',
                    'contractor_id' => null
                ];
            }
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

            // Invoice data from contractor only
            $allInvoices = Invoice::where('client_id', $client->id)
                ->where('contractor_id', $contractorId)
                ->get();
            $pendingInvoices = $allInvoices->whereIn('status', ['draft', 'sent', 'overdue']);
            $paidInvoices = $allInvoices->where('status', 'paid');
            $recentInvoices = $allInvoices->sortByDesc('invoice_date')->take(5);

            // Monthly payments from contractor (last 12 months)
            $monthlyPayments = Invoice::where('client_id', $client->id)
                ->where('contractor_id', $contractorId)
                ->where('status', 'paid')
                ->where('paid_at', '>=', now()->subMonths(12))
                ->selectRaw('strftime("%Y", paid_at) as year, strftime("%m", paid_at) as month, SUM(total_amount) as total')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();

            // Feedback between client and contractor
            $recentFeedback = Feedback::where('client_id', $client->id)
                ->where('contractor_id', $contractorId)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            // Payment statistics from contractor
            $totalPaid = $paidInvoices->sum('total_amount');
            $totalPending = $pendingInvoices->sum('total_amount');
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
        }

        return view('client.dashboard', compact(
            'client', 
            'upcomingSchedules', 
            'recentInvoices',
            'allSchedules',
            'missedPickups',
            'completedPickups',
            'pendingInvoices',
            'paidInvoices',
            'monthlyPayments',
            'recentFeedback',
            'totalPaid',
            'totalPending'
        ));
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
        ]);

        $client->update($validated);
        return redirect()->route('client.profile')->with('success', 'Profile updated successfully.');
    }

    public function schedules()
    {
        $client = $this->resolveClient();
        if (!$client) {
            $client = Client::where('registration_number', 'CL041204')->first();
        }
        abort_unless($client, 404);

        $schedules = Schedule::with('contractor')
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
        return view('client_portal.request_service', compact('client', 'products'));
    }

    public function storeServiceRequest(Request $request)
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $validated = $request->validate([
            'service_type' => 'required|string',
            'pickup_date' => 'required|date|after:today',
            'pickup_time' => 'required|string',
            'waste_type' => 'required|string',
            'estimated_volume' => 'required|string',
            'special_instructions' => 'nullable|string|max:1000',
        ]);

        Schedule::create([
            'client_id' => $client->id,
            'contractor_id' => $client->contractor_id,
            'pickup_date' => $validated['pickup_date'],
            'pickup_time' => $validated['pickup_time'],
            'waste_type' => $validated['waste_type'],
            'estimated_volume' => $validated['estimated_volume'],
            'special_instructions' => $validated['special_instructions'],
            'status' => 'pending',
        ]);

        return redirect()->route('client.schedules')->with('success', 'Service request submitted successfully.');
    }

    public function equipment()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);
        
        $products = Product::all();
        return view('client_portal.equipment', compact('client', 'products'));
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

        $invoices = Invoice::with(['contractor'])
            ->where('client_id', $client->id)
            ->where('contractor_id', $client->contractor_id)
            ->orderByDesc('invoice_date')
            ->paginate(15);

        return view('client_portal.invoices', compact('client', 'invoices'));
    }

    public function payments()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $payments = Invoice::where('client_id', $client->id)
            ->where('contractor_id', $client->contractor_id)
            ->where('status', 'paid')
            ->orderByDesc('paid_at')
            ->paginate(15);

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

        return redirect()->route('client.feedback')->with('success', 'Feedback submitted successfully.');
    }
}
