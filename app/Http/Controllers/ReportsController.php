<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $data = [
            'clientStats' => $this->getClientStats(),
            'billingStats' => $this->getBillingStats(),
            'collectionStats' => $this->getCollectionStats(),
            'disposalStats' => $this->getDisposalStats()
        ];

        return view('reports.index', compact('data'));
    }

    private function getClientStats()
    {
        $contractorId = Auth::id();
        
        return [
            'total_clients' => Client::where('contractor_id', $contractorId)->count(),
            'by_category' => Client::where('contractor_id', $contractorId)
                ->select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get(),
            'by_location' => Client::where('contractor_id', $contractorId)
                ->select('address', DB::raw('count(*) as count'))
                ->groupBy('address')
                ->get()
        ];
    }

    private function getBillingStats()
    {
        $contractorId = Auth::id();
        
        return [
            'total_revenue' => Invoice::where('contractor_id', $contractorId)->sum('total_amount'),
            'paid_revenue' => Invoice::where('contractor_id', $contractorId)->sum('amount_paid'),
            'pending_payments' => Invoice::where('contractor_id', $contractorId)
                ->where('status', '!=', 'paid')
                ->sum('total_amount'),
            'weekly_revenue' => Invoice::where('contractor_id', $contractorId)
                ->where('created_at', '>=', now()->startOfWeek())
                ->sum('amount_paid'),
            'monthly_revenue' => Invoice::where('contractor_id', $contractorId)
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('amount_paid'),
            'yearly_revenue' => Invoice::where('contractor_id', $contractorId)
                ->where('created_at', '>=', now()->startOfYear())
                ->sum('amount_paid'),
            'paid_customers' => Invoice::where('contractor_id', $contractorId)
                ->where('status', 'paid')
                ->distinct('client_id')
                ->count(),
            'overdue_customers' => Invoice::where('contractor_id', $contractorId)
                ->where('due_date', '<', now())
                ->where('status', '!=', 'paid')
                ->distinct('client_id')
                ->count()
        ];
    }

    private function getCollectionStats()
    {
        $contractorId = Auth::id();
        
        return [
            'total_routes' => Schedule::where('contractor_id', $contractorId)
                ->distinct('pickup_location')
                ->count(),
            'completed_collections' => Schedule::where('contractor_id', $contractorId)
                ->where('status', 'completed')
                ->count(),
            'volumes_by_route' => Schedule::where('contractor_id', $contractorId)
                ->whereNotNull('total_volume')
                ->select('pickup_location', DB::raw('sum(total_volume) as total_volume'))
                ->groupBy('pickup_location')
                ->get()
        ];
    }

    private function getDisposalStats()
    {
        $contractorId = Auth::id();
        
        return [
            'total_volume_collected' => Schedule::where('contractor_id', $contractorId)
                ->sum('total_volume'),
            'volumes_by_disposal_type' => Schedule::where('contractor_id', $contractorId)
                ->whereNotNull('disposal_type')
                ->select('disposal_type', DB::raw('sum(total_volume) as total_volume'))
                ->groupBy('disposal_type')
                ->get(),
            'recycled_volume' => Schedule::where('contractor_id', $contractorId)
                ->where('disposal_type', 'sorting_facility')
                ->sum('total_volume')
        ];
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'summary');
        $data = [
            'clientStats' => $this->getClientStats(),
            'billingStats' => $this->getBillingStats(),
            'collectionStats' => $this->getCollectionStats(),
            'disposalStats' => $this->getDisposalStats()
        ];

        return view('reports.export', compact('data', 'type'));
    }
}