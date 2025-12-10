<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
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
        $contractorId = Auth::id();
        
        $stats = [
            'total_clients' => Client::where('contractor_id', $contractorId)->count(),
            'total_invoices' => Invoice::where('contractor_id', $contractorId)->count(),
            'pending_payments' => Invoice::where('contractor_id', $contractorId)
                ->whereIn('status', ['draft', 'sent', 'overdue'])
                ->sum('total_amount'),
            'active_routes' => Schedule::where('contractor_id', $contractorId)
                ->distinct('pickup_location')
                ->count()
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
}