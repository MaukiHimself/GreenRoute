<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\ContractorRoute;
use App\Models\ContractorLocation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get system parameters
        $contractorsCount = User::where('user_type', 'contractor')->count();
        $clientsCount = Client::count();
        $activeRoutesCount = ContractorRoute::where('is_active', true)->count();
        
        // Get pending verifications count (contractors without approved status)
        $pendingVerifications = User::where('user_type', 'contractor')
            ->where('status', '!=', 'approved')
            ->count();
        
        // Pending tasks
        $pendingTasks = [];
        
        // Check for contractors pending verification
        if ($pendingVerifications > 0) {
            $pendingTasks[] = [
                'icon' => 'person-check',
                'title' => 'Verify Contractor',
                'description' => 'New contractor registrations awaiting approval',
                'count' => $pendingVerifications,
                'link' => route('admin.verification')
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
            'pendingTasks' => $pendingTasks
        ]);
    }

    public function verification()
    {
        // Get contractors pending verification
        $pendingContractors = User::where('user_type', 'contractor')
            ->where('status', '!=', 'approved')
            ->with('contractor')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.verification', [
            'pendingContractors' => $pendingContractors
        ]);
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

    public function schedules()
    {
        // Get all schedules with client and contractor relationships
        $schedules = \App\Models\Schedule::with(['client', 'contractor'])
            ->orderBy('scheduled_date', 'desc')
            ->paginate(20);
        
        // Calculate statistics
        $totalSchedules = \App\Models\Schedule::count();
        $completedSchedules = \App\Models\Schedule::where('status', 'completed')->count();
        $pendingSchedules = \App\Models\Schedule::where('status', 'pending')->count();
        $todaySchedules = \App\Models\Schedule::whereDate('scheduled_date', today())->count();
        
        return view('admin.schedules', [
            'schedules' => $schedules,
            'totalSchedules' => $totalSchedules,
            'completedSchedules' => $completedSchedules,
            'pendingSchedules' => $pendingSchedules,
            'todaySchedules' => $todaySchedules
        ]);
    }

    public function users()
    {
        // Get all users
        $users = User::orderBy('created_at', 'desc')->paginate(50);
        
        return view('admin.users', [
            'users' => $users
        ]);
    }

    public function getContractorLocations()
    {
        $contractors = User::where('user_type', 'contractor')
            ->with(['contractorLocations' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->filter(function($contractor) {
                return $contractor->contractorLocations->isNotEmpty();
            })
            ->map(function($contractor) {
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
        // Update user status to approved
        $user->update(['status' => 'approved']);

        // Send approval email notification
        try {
            \Mail::to($user->email)->send(new \App\Mail\ContractorApproved($user));
        } catch (\Exception $e) {
            // Log the error but don't fail the approval
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }

        return redirect()->route('admin.verification')
            ->with('success', "Contractor {$user->name} has been approved successfully. A confirmation email has been sent.");
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
}