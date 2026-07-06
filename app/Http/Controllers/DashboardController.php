<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\PaymentSubmission;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Register middleware through the middleware method of the BaseController class
        $this->middleware('auth');
    }

    /**
     * Show the appropriate dashboard based on user type.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->user_type === 'client') {
            return redirect()->route('client.dashboard');
        } elseif (Auth::user()->user_type === 'contractor') {
            return redirect()->route('dashboard.contractor');
        } elseif (Auth::user()->user_type === 'admin') {
            return redirect()->route('dashboard.admin');
        } else {
            // Default dashboard if user type is not recognized
            return view('dashboard');
        }
    }

    /**
     * Show the client dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function clientDashboard()
    {
        if (Auth::user()->user_type !== 'client') {
            return redirect()->route('dashboard');
        }
        
        return redirect()->route('client.dashboard');
    }

    /**
     * Show the contractor dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contractorDashboard($tab = null)
    {
        if (Auth::user()->user_type !== 'contractor') {
            return redirect()->route('dashboard');
        }

        $contractorId = Auth::id();

        // Stats — loaded server-side so the page renders immediately
        $stats = [
            'total_clients'   => Client::where('contractor_id', $contractorId)->count(),
            'active_clients'  => Client::where('contractor_id', $contractorId)->where('status', 'active')->count(),
            'pending_clients' => Client::where('contractor_id', $contractorId)->where('status', 'pending')->where('self_registered', true)->count(),
            'total_invoices'  => Invoice::where('contractor_id', $contractorId)->count(),
            'pending_payments'=> Invoice::where('contractor_id', $contractorId)
                ->whereIn('status', ['draft', 'sent', 'overdue', 'partially_paid'])
                ->sum('remaining_balance') ?? 0,
            'active_routes'   => Schedule::where('contractor_id', $contractorId)
                ->where('pickup_date', '>=', now()->toDateString())
                ->where('status', '!=', 'cancelled')
                ->distinct('pickup_location')
                ->count(),
            'completed_jobs'  => Schedule::where('contractor_id', $contractorId)->where('status', 'completed')->count(),
            'pending_approvals' => PaymentSubmission::where('contractor_id', $contractorId)
                ->whereIn('status', ['pending', 'pending_approval'])
                ->count(),
        ];

        // Recent invoices
        $recentInvoices = Invoice::where('contractor_id', $contractorId)
            ->with('client:id,name')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Upcoming schedules
        $upcomingSchedules = Schedule::where('contractor_id', $contractorId)
            ->with('client:id,name')
            ->where('pickup_date', '>=', now()->toDateString())
            ->where('status', '!=', 'cancelled')
            ->orderBy('pickup_date')
            ->limit(5)
            ->get();

        // Recent pending payment submissions
        $pendingPayments = PaymentSubmission::where('contractor_id', $contractorId)
            ->where('status', 'pending_approval')
            ->with(['invoice:id,invoice_number', 'client:id,name'])
            ->orderByDesc('submitted_at')
            ->limit(5)
            ->get();

        return view('contractor.mapping-dashboard', compact('stats', 'recentInvoices', 'upcomingSchedules', 'pendingPayments'));
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminDashboard()
    {
        if (Auth::user()->user_type !== 'admin') {
            return redirect()->route('dashboard');
        }
        
        return view('admin.tracking-dashboard');
    }
}