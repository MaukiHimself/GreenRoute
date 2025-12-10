<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClientGroupController extends Controller
{
    /**
     * Display client groups organized by site location
     */
    public function index()
    {
        $contractorId = Auth::id();
        
        // Get all clients for this contractor grouped by location
        $clientGroups = Client::where('contractor_id', $contractorId)
            ->whereNotNull('region')
            ->select('region', 'district', 'ward', 'street', DB::raw('count(*) as client_count'))
            ->groupBy('region', 'district', 'ward', 'street')
            ->orderBy('region')
            ->orderBy('district')
            ->orderBy('ward')
            ->orderBy('street')
            ->get()
            ->map(function($group) use ($contractorId) {
                // Build location string
                $locationParts = array_filter([
                    $group->region,
                    $group->district,
                    $group->ward,
                    $group->street
                ]);
                $group->location_name = implode(' → ', $locationParts);
                
                // Get clients in this group
                $group->clients = Client::where('contractor_id', $contractorId)
                    ->where('region', $group->region)
                    ->where('district', $group->district)
                    ->where(function($query) use ($group) {
                        $query->where('ward', $group->ward)
                              ->orWhereNull('ward');
                    })
                    ->where(function($query) use ($group) {
                        $query->where('street', $group->street)
                              ->orWhereNull('street');
                    })
                    ->select('id', 'name', 'registration_number', 'address', 'phone', 'email', 'category')
                    ->orderBy('name')
                    ->get();
                
                // Determine group status based on client statuses
                $group->status = 'active'; // Default status
                
                return $group;
            });
        
        // Get regions for filtering
        $regions = [];
        if (Schema::hasTable('tbl_locations')) {
            try {
                $regions = Location::select('region')
                    ->distinct()
                    ->orderBy('region')
                    ->pluck('region');
            } catch (\Exception $e) {
                $regions = [];
            }
        }
        
        return view('client-groups.index', compact('clientGroups', 'regions'));
    }
    
    /**
     * Get clients for a specific location group (AJAX)
     */
    public function getGroupClients(Request $request)
    {
        $validated = $request->validate([
            'region' => 'required|string',
            'district' => 'required|string',
            'ward' => 'nullable|string',
            'street' => 'nullable|string',
        ]);
        
        $query = Client::where('contractor_id', Auth::id())
            ->where('region', $validated['region'])
            ->where('district', $validated['district']);
        
        if (!empty($validated['ward'])) {
            $query->where('ward', $validated['ward']);
        }
        
        if (!empty($validated['street'])) {
            $query->where('street', $validated['street']);
        }
        
        $clients = $query->select('id', 'name', 'registration_number', 'address', 'phone', 'email', 'category')
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'clients' => $clients
        ]);
    }
    
    /**
     * Redirect to create schedule for selected groups
     */
    public function createScheduleForGroups(Request $request)
    {
        $validated = $request->validate([
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
        ]);
        
        // Store selected client IDs in session to pre-populate the schedule form
        session(['selected_client_ids' => $validated['client_ids']]);
        
        return redirect()->route('schedules.create')
            ->with('info', 'Selected ' . count($validated['client_ids']) . ' clients. Please complete the schedule details.');
    }
    
    /**
     * Redirect to create invoice for selected groups
     */
    public function createInvoiceForGroups(Request $request)
    {
        $validated = $request->validate([
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
        ]);
        
        // Store selected client IDs in session to pre-populate the invoice form
        session(['selected_client_ids' => $validated['client_ids']]);
        
        return redirect()->route('invoices.create')
            ->with('info', 'Selected ' . count($validated['client_ids']) . ' clients. Please complete the invoice details.');
    }
}
