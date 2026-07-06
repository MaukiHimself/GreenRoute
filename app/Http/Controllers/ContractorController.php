<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\PaymentSubmission;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContractorController extends Controller
{
    public function dashboard()
    {
        $contractorId = Auth::id();
        
        $stats = [
            'total_clients' => Client::where('contractor_id', $contractorId)->count(),
            'active_clients' => Client::where('contractor_id', $contractorId)->where('status', 'active')->count(),
            'pending_clients' => Client::where('contractor_id', $contractorId)->where('status', 'pending')->where('self_registered', true)->count(),
            'total_routes' => \App\Models\ContractorRoute::where('contractor_id', $contractorId)->where('is_active', true)->count(),
            'completed_jobs' => Schedule::where('contractor_id', $contractorId)->where('status', 'completed')->count(),
            'monthly_revenue' => Invoice::where('contractor_id', $contractorId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
        ];
        
        return view('contractor.dashboard', compact('stats'));
    }

    public function getAssignedClients()
    {
        $query = Client::where('contractor_id', auth()->id());

        // Apply search filters
        if (request('name')) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }

        if (request('category')) {
            $query->where('category', request('category'));
        }

        if (request('location')) {
            $query->where('address', 'like', '%' . request('location') . '%');
        }

        if (request('registration_number')) {
            $query->where('registration_number', 'like', '%' . request('registration_number') . '%');
        }

        $clients = $query->select('id', 'name', 'contact_name', 'category', 'registration_number', 'address', 'latitude', 'longitude', 'phone', 'phone_2', 'phone_3', 'email', 'email_2', 'email_3', 'status', 'self_registered', 'route', 'region')
            ->get();

        return response()->json($clients);
    }

    public function getDashboardStats()
    {
        try {
            $contractorId = Auth::id();

            $stats = [
                'total_clients' => Client::where('contractor_id', $contractorId)->count(),
                'total_invoices' => Invoice::where('contractor_id', $contractorId)->count(),
                'pending_payments' => Invoice::where('contractor_id', $contractorId)
                    ->whereIn('status', ['draft', 'sent', 'overdue', 'partially_paid'])
                    ->sum('remaining_balance') ?? 0,
                'active_routes' => Schedule::where('contractor_id', $contractorId)
                    ->where('pickup_date', '>=', now()->toDateString())
                    ->distinct('pickup_location')
                    ->count(),
                'new_payment_notifications' => PaymentSubmission::where('contractor_id', $contractorId)
                    ->whereIn('status', ['pending', 'pending_approval'])
                    ->count(),
                'completed_jobs' => Schedule::where('contractor_id', $contractorId)
                    ->where('status', 'completed')
                    ->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Dashboard stats error: ' . $e->getMessage());
            return response()->json([
                'total_clients' => 0,
                'total_invoices' => 0,
                'pending_payments' => 0,
                'active_routes' => 0,
                'new_payment_notifications' => 0,
                'completed_jobs' => 0,
            ]);
        }
    }

    public function getRecentInvoices()
    {
        try {
            $invoices = Invoice::where('contractor_id', Auth::id())
                ->with('client')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($invoice) {
                    return [
                        'id' => $invoice->id,
                        'client_name' => $invoice->client ? $invoice->client->name : 'Unknown Client',
                        'total_amount' => number_format($invoice->total_amount, 2),
                        'status' => $invoice->status
                    ];
                });

            return response()->json($invoices);
        } catch (\Exception $e) {
            \Log::error('Recent invoices error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

     public function getUpcomingSchedules()
     {
         try {
             $schedules = Schedule::where('contractor_id', Auth::id())
                 ->with('client')
                 ->where('pickup_date', '>=', now()->toDateString())
                 ->where('status', '!=', 'cancelled')
                 ->orderBy('pickup_date')
                 ->limit(5)
                 ->get()
                 ->map(function($schedule) {
                     return [
                         'pickup_location' => $schedule->pickup_location,
                         'client_name' => $schedule->client ? $schedule->client->name : 'Unknown Client',
                         'pickup_date' => $schedule->pickup_date ? $schedule->pickup_date->format('M d, Y') : 'N/A',
                         'pickup_time' => $schedule->pickup_time,
                         'schedule_price' => $schedule->displayed_price ? number_format($schedule->displayed_price, 2) : null
                     ];
                 });

             return response()->json($schedules);
         } catch (\Exception $e) {
             \Log::error('Upcoming schedules error: ' . $e->getMessage());
             return response()->json([]);
         }
     }

     public function getRecentPendingPayments()
     {
         try {
             $contractorId = Auth::id();

             $recentPendingPayments = PaymentSubmission::where('contractor_id', $contractorId)
                 ->where('status', 'pending_approval')
                 ->with(['invoice', 'client'])
                 ->orderBy('submitted_at', 'desc')
                 ->limit(5)
                 ->get()
                 ->map(function($payment) {
                     return [
                         'id' => $payment->id,
                         'client_name' => $payment->client ? $payment->client->name : 'Unknown Client',
                         'payer_name' => $payment->payer_name,
                         'amount_submitted' => number_format($payment->amount_submitted, 2),
                         'invoice_number' => $payment->invoice ? $payment->invoice->invoice_number : 'N/A',
                         'payment_method' => $payment->payment_method ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : 'Unknown',
                         'submitted_at' => $payment->submitted_at ? $payment->submitted_at->format('M d, Y H:i') : 'N/A',
                         'status' => $payment->status
                     ];
                 });

             return response()->json($recentPendingPayments);
         } catch (\Exception $e) {
             \Log::error('Recent pending payments error: ' . $e->getMessage());
             return response()->json([]);
         }
     }

     public function getRecentPayments()
     {
         $contractorId = Auth::id();

         $payments = PaymentSubmission::where('contractor_id', $contractorId)
             ->with(['invoice', 'client'])
             ->orderBy('submitted_at', 'desc')
             ->limit(5)
             ->get()
             ->map(function($payment) {
                 return [
                     'id' => $payment->id,
                     'client_name' => $payment->client ? $payment->client->name : 'Unknown Client',
                     'amount_submitted' => number_format($payment->amount_submitted, 2),
                     'invoice_number' => $payment->invoice ? $payment->invoice->invoice_number : 'N/A',
                     'payment_method' => $payment->payment_method ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : 'Unknown',
                     'submitted_at' => $payment->submitted_at ? $payment->submitted_at->format('M d, Y H:i') : 'N/A',
                     'status' => $payment->status
                 ];
             });

         return response()->json($payments);
     }

     // SMS Campaign Methods
     public function smsCampaign()
     {
         $contractorId = Auth::id();
         
         $clients = Client::where('contractor_id', $contractorId)
             ->whereNotNull('phone')
             ->orderBy('name')
             ->get();

         return view('contractor.sms-campaign', compact('clients'));
     }

     public function sendSmsCampaign(Request $request)
     {
         $validated = $request->validate([
             'recipients' => 'required|in:all,residential,commercial,selected',
             'selected_clients' => 'required_if:recipients,selected|array',
             'selected_clients.*' => 'exists:clients,id',
             'message' => 'required|string|max:500',
             'campaign_name' => 'required|string|max:255'
         ]);

         $contractorId = Auth::id();
         $recipients = $this->getSmsCampaignRecipients($request, $contractorId);

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

         \Log::info("SMS Campaign '{$validated['campaign_name']}' by contractor {$contractorId}: {$successCount} sent, {$failCount} failed");

         return redirect()->route('contractor.sms.campaign')
             ->with('success', "SMS Campaign sent successfully! {$successCount} messages sent, {$failCount} failed.");
     }

     private function getSmsCampaignRecipients(Request $request, $contractorId)
     {
         $query = Client::where('contractor_id', $contractorId)->whereNotNull('phone');
         
         switch ($request->recipients) {
             case 'all':
                 return $query->get();
             case 'residential':
                 return $query->where('category', 'residential')->get();
             case 'commercial':
                 return $query->where('category', 'commercial')->get();
             case 'selected':
                 return $query->whereIn('id', $request->selected_clients)->get();
             default:
                 return collect();
         }
     }

     private function sendSms($phone, $message)
     {
         // Placeholder for SMS integration
         // TODO: Integrate with SMS service provider (Twilio, Africa's Talking, etc.)
         \Log::info("SMS to {$phone}: {$message}");
         return true;
     }

     public function clientsMap()
     {
         $contractorId = Auth::id();
         $clients = Client::where('contractor_id', $contractorId)
             ->whereNotNull('latitude')
             ->whereNotNull('longitude')
             ->select('id', 'name', 'latitude', 'longitude', 'address', 'phone', 'city', 'route', 'category')
             ->get();

         return view('contractor.clients-map', compact('clients'));
     }
}
