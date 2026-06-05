<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\PaymentSubmission;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ContractorController extends Controller
{
    public function dashboard()
    {
        return view('contractor.dashboard');
    }

    public function getAssignedClients()
    {
        $query = Client::where('contractor_id', auth()->id())
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

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

        $clients = $query->select('id', 'name', 'contact_name', 'category', 'registration_number', 'address', 'latitude', 'longitude', 'phone', 'phone_2', 'phone_3', 'email', 'email_2', 'email_3')
            ->get();

        return response()->json($clients);
    }

    public function getDashboardStats()
    {
        $contractor = Auth::user()->contractor;
        if (!$contractor) return response()->json([]);
        $contractorId = $contractor->id;

        $stats = [
            'total_clients' => Client::where('contractor_id', Auth::id())->count(),
            'total_invoices' => Invoice::where('contractor_id', Auth::id())->count(),
            'pending_payments' => Invoice::where('contractor_id', Auth::id())
                ->whereIn('status', ['draft', 'sent', 'overdue', 'partially_paid'])
                ->sum('remaining_balance'),
            'active_routes' => Schedule::where('contractor_id', Auth::id())
                ->distinct('pickup_location')
                ->count(),
            'new_payment_notifications' => PaymentSubmission::where('contractor_id', $contractorId)
                ->whereIn('status', ['pending', 'pending_approval'])
                ->count(),
        ];

        return response()->json($stats);
    }

    public function getRecentInvoices()
    {
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
    }

     public function getUpcomingSchedules()
     {
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
                     'pickup_date' => $schedule->pickup_date->format('M d, Y'),
                     'pickup_time' => $schedule->pickup_time
                 ];
             });

         return response()->json($schedules);
     }

     public function getRecentPendingPayments()
     {
         $contractor = Auth::user()->contractor;
         if (!$contractor) return response()->json([]);
         $contractorId = $contractor->id;

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
     }
}
