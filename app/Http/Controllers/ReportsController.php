<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CollectionRun;
use App\Models\CollectionRunStop;
use App\Models\ContractorRoute;
use App\Models\Invoice;
use App\Models\Schedule;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $data = $this->buildReportData();

        return view('reports.index', compact('data'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'summary');
        $data = $this->buildReportData();

        return view('reports.export', compact('data', 'type'));
    }

    private function buildReportData(): array
    {
        return [
            'overview' => $this->getOverviewStats(),
            'clientStats' => $this->getClientStats(),
            'billingStats' => $this->getBillingStats(),
            'operationsStats' => $this->getOperationsStats(),
            'disposalStats' => $this->getDisposalStats(),
            'monthlyWaste' => $this->getMonthlyWasteTrend(),
        ];
    }

    /**
     * Top-line numbers: what the contractor owns and how much work got done.
     */
    private function getOverviewStats(): array
    {
        $contractorId = Auth::id();

        return [
            'total_clients' => Client::where('contractor_id', $contractorId)->count(),
            'total_routes' => ContractorRoute::where('contractor_id', $contractorId)->count(),
            'total_trucks' => Truck::where('contractor_id', $contractorId)->count(),
            'runs_completed' => CollectionRun::where('contractor_id', $contractorId)
                ->where('status', 'completed')
                ->count(),
        ];
    }

    private function getClientStats(): array
    {
        $contractorId = Auth::id();

        return [
            'total_clients' => Client::where('contractor_id', $contractorId)->count(),
            'new_this_month' => Client::where('contractor_id', $contractorId)
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
            'by_category' => Client::where('contractor_id', $contractorId)
                ->select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get(),
            'by_route' => Client::where('contractor_id', $contractorId)
                ->whereNotNull('route')
                ->select('route', DB::raw('count(*) as count'))
                ->groupBy('route')
                ->orderByDesc('count')
                ->get(),
        ];
    }

    private function getBillingStats(): array
    {
        $contractorId = Auth::id();

        return [
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
                ->count(),
        ];
    }

    /**
     * Field operations from collection runs: how the trucks actually performed,
     * and the waste weights measured at the dumping-site weighbridge.
     */
    private function getOperationsStats(): array
    {
        $contractorId = Auth::id();

        $runTotals = CollectionRun::where('contractor_id', $contractorId)
            ->where('status', 'completed')
            ->selectRaw('
                COALESCE(SUM(collected_count), 0) as collected,
                COALESCE(SUM(skipped_count), 0) as skipped,
                COALESCE(SUM(blocked_count), 0) as blocked
            ')
            ->first();

        $actioned = $runTotals->collected + $runTotals->skipped + $runTotals->blocked;

        $weighedBase = CollectionRun::where('contractor_id', $contractorId)
            ->whereNotNull('net_weight_kg');

        return [
            'stops_collected' => (int) $runTotals->collected,
            'stops_skipped' => (int) $runTotals->skipped,
            'stops_blocked' => (int) $runTotals->blocked,
            'success_rate' => $actioned > 0 ? round($runTotals->collected / $actioned * 100) : null,
            'trips_weighed' => (clone $weighedBase)->count(),
            'total_waste_kg' => (clone $weighedBase)->sum('net_weight_kg'),
            'month_waste_kg' => (clone $weighedBase)
                ->where('weighed_at', '>=', now()->startOfMonth())
                ->sum('net_weight_kg'),
            'waste_by_route' => CollectionRun::where('contractor_id', $contractorId)
                ->whereNotNull('net_weight_kg')
                ->select('route_name', DB::raw('SUM(net_weight_kg) as total_kg'), DB::raw('COUNT(*) as trips'))
                ->groupBy('route_name')
                ->orderByDesc('total_kg')
                ->get(),
            'top_clients_by_waste' => CollectionRunStop::query()
                ->join('collection_runs', 'collection_runs.id', '=', 'collection_run_stops.collection_run_id')
                ->where('collection_runs.contractor_id', $contractorId)
                ->whereNotNull('collection_run_stops.prorated_weight_kg')
                ->select('collection_run_stops.client_name',
                    DB::raw('SUM(collection_run_stops.prorated_weight_kg) as total_kg'),
                    DB::raw('COUNT(*) as pickups'))
                ->groupBy('collection_run_stops.client_name')
                ->orderByDesc('total_kg')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Disposal records captured on completed schedules (the kg-based form),
     * powering the recycling / landfill and waste-category breakdowns.
     */
    private function getDisposalStats(): array
    {
        $contractorId = Auth::id();

        $recorded = Schedule::where('contractor_id', $contractorId)
            ->whereNotNull('weight_kg');

        return [
            'recorded_weight_kg' => (clone $recorded)->sum('weight_kg'),
            'recycled_kg' => (clone $recorded)
                ->where('disposal_type', 'sorting_facility')
                ->sum('weight_kg'),
            'landfill_kg' => (clone $recorded)
                ->where('disposal_type', 'landfill')
                ->sum('weight_kg'),
            'pending_records' => Schedule::where('contractor_id', $contractorId)
                ->where('status', 'completed')
                ->whereNull('weight_kg')
                ->count(),
            'by_category' => (clone $recorded)
                ->select('waste_category', DB::raw('SUM(weight_kg) as total_kg'))
                ->groupBy('waste_category')
                ->orderByDesc('total_kg')
                ->get(),
            'by_site' => (clone $recorded)
                ->whereNotNull('disposal_site')
                ->select('disposal_site', DB::raw('SUM(weight_kg) as total_kg'))
                ->groupBy('disposal_site')
                ->orderByDesc('total_kg')
                ->get(),
        ];
    }

    /**
     * Last six months of waste weight from both recording channels:
     * weighbridge readings on trips, and per-schedule disposal records.
     */
    private function getMonthlyWasteTrend(): array
    {
        $contractorId = Auth::id();

        $labels = [];
        $tripSeries = [];
        $scheduleSeries = [];

        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $labels[] = $start->format('M Y');

            $tripSeries[] = (float) CollectionRun::where('contractor_id', $contractorId)
                ->whereBetween('weighed_at', [$start, $end])
                ->sum('net_weight_kg');

            $scheduleSeries[] = (float) Schedule::where('contractor_id', $contractorId)
                ->whereNotNull('weight_kg')
                ->whereBetween('updated_at', [$start, $end])
                ->sum('weight_kg');
        }

        return [
            'labels' => $labels,
            'trip_kg' => $tripSeries,
            'schedule_kg' => $scheduleSeries,
        ];
    }
}
