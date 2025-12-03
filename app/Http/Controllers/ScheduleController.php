<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Client;
use App\Models\Location;
use App\Models\ContractorRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        // Only show non-completed schedules (completed ones go to disposal tab)
        $schedules = Schedule::forContractor(Auth::id())
            ->whereIn('status', ['scheduled', 'in_progress', 'cancelled'])
            ->with('client')
            ->orderBy('pickup_date', 'desc')
            ->paginate(15);

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        // Check if user is a contractor and redirect to contractor-specific view
        if (Auth::user()->user_type === 'contractor') {
            $contractor = Auth::user();
            $clients = Client::where('contractor_id', Auth::id())->get();
            $assignedClient = Client::where('contractor_id', Auth::id())->first();
            
            // Get simplified site locations - only from routes that this contractor has created
            // This is much faster than loading all locations from tbl_locations
            $contractorRoutes = ContractorRoute::where('contractor_id', Auth::id())
                ->where('is_active', true)
                ->whereNotNull('region')
                ->whereNotNull('district')
                ->select('region', 'district', 'ward', 'street')
                ->distinct()
                ->orderBy('region')
                ->orderBy('district')
                ->get();
            
            $siteLocations = $contractorRoutes->map(function ($route) {
                return [
                    'full' => implode(' - ', array_filter([
                        $route->region,
                        $route->district,
                        $route->ward,
                        $route->street
                    ])),
                    'region' => $route->region,
                    'district' => $route->district,
                    'ward' => $route->ward,
                    'street' => $route->street,
                ];
            })->unique('full')->values();
            
            // Get routes with their assigned locations (only active routes with locations)
            $routes = ContractorRoute::where('contractor_id', Auth::id())
                ->where('is_active', true)
                ->whereNotNull('region')
                ->select('id', 'route_name', 'region', 'district', 'ward', 'street')
                ->orderBy('route_name')
                ->get();
            
            return view('contractor.create-schedule', compact('contractor', 'clients', 'assignedClient', 'siteLocations', 'routes'));
        }
        
        // Get site locations from tbl_locations grouped by Region → District
        $siteLocations = Location::select('region', 'district')
            ->distinct()
            ->orderBy('region')
            ->orderBy('district')
            ->get()
            ->groupBy('region')
            ->map(function ($districts) {
                return $districts->pluck('district')->unique()->values();
            });
        
        // Get route names from contractor's routes
        $routeNames = ContractorRoute::where('contractor_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('route_name')
            ->pluck('route_name');

        return view('schedules.create', compact('siteLocations', 'routeNames'));
    }

    public function store(Request $request)
    {
        // Check if this is route-based multi-client creation
        if ($request->has('route')) {
            $route = $request->input('route');
            
            // Custom route (single client) - route name is now in 'route' field
            if ($request->has('custom_client_id')) {
                $validated = $request->validate([
                    'route' => 'required|string|max:255',
                    'custom_client_id' => 'required|exists:clients,id',
                    'pickup_date' => 'required|date',
                    'pickup_time' => 'required|date_format:H:i',
                    'pickup_location' => 'required|string|max:255',
                    'pickup_address' => 'required|string',
                    'city' => 'required|string',
                    'state' => 'required|string',
                    'zip_code' => 'required|string',
                    'service_type' => 'required|in:collection,disposal,both',
                    'estimated_duration' => 'nullable|numeric',
                    'total_volume' => 'nullable|numeric',
                    'disposal_site' => 'nullable|string',
                    'notes' => 'nullable|string'
                ]);

                Schedule::create([
                    'contractor_id' => Auth::id(),
                    'client_id' => $validated['custom_client_id'],
                    'route' => $validated['route'],
                    'pickup_date' => $validated['pickup_date'],
                    'pickup_time' => $validated['pickup_time'],
                    'pickup_location' => $validated['pickup_location'],
                    'pickup_address' => $validated['pickup_address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'zip_code' => $validated['zip_code'],
                    'service_type' => $validated['service_type'],
                    'status' => 'scheduled',
                    'estimated_duration' => $validated['estimated_duration'] ?? null,
                    'total_volume' => $validated['total_volume'] ?? null,
                    'disposal_site' => $validated['disposal_site'] ?? null,
                    'notes' => $validated['notes'] ?? null
                ]);

                return redirect()->route('schedules.index')->with('success', 'Schedule created successfully');
            }
            
            // Route-based multi-client creation
            if ($request->has('client_ids')) {
                $validated = $request->validate([
                    'route' => 'required|string',
                    'client_ids' => 'required|array|min:1',
                    'client_ids.*' => 'exists:clients,id',
                    'pickup_date' => 'required|date',
                    'pickup_time' => 'required|date_format:H:i',
                    'pickup_location' => 'required|string|max:255',
                    'service_type' => 'required|in:collection,disposal,both',
                    'estimated_duration' => 'nullable|numeric',
                    'total_volume' => 'nullable|numeric',
                    'disposal_site' => 'nullable|string',
                    'notes' => 'nullable|string'
                ]);

                // Generate unique group ID for this route schedule
                $routeGroupId = uniqid('route_' . $validated['route'] . '_', true);

                // Create schedule for each selected client
                $clients = Client::whereIn('id', $validated['client_ids'])->get();
                
                foreach ($clients as $client) {
                    Schedule::create([
                        'contractor_id' => Auth::id(),
                        'client_id' => $client->id,
                        'route' => $validated['route'],
                        'route_group_id' => $routeGroupId,
                        'pickup_date' => $validated['pickup_date'],
                        'pickup_time' => $validated['pickup_time'],
                        'pickup_location' => $validated['pickup_location'],
                        'pickup_address' => $client->address,
                        'city' => $client->city,
                        'state' => $client->state,
                        'zip_code' => $client->zip_code,
                        'service_type' => $validated['service_type'],
                        'status' => 'scheduled',
                        'estimated_duration' => $validated['estimated_duration'] ?? null,
                        'total_volume' => $validated['total_volume'] ?? null,
                        'disposal_site' => $validated['disposal_site'] ?? null,
                        'notes' => $validated['notes'] ?? null
                    ]);
                }

                $clientCount = count($validated['client_ids']);
                return redirect()->route('schedules.index')->with('success', "Route schedule created successfully for {$clientCount} clients");
            }
        }
        
        // Legacy: Check if this is from the old contractor-specific form (has client_id)
        if ($request->has('client_id')) {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'pickup_date' => 'required|date',
                'pickup_time' => 'required|date_format:H:i',
                'pickup_location' => 'required|string|max:255',
                'pickup_address' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'zip_code' => 'required|string',
                'service_type' => 'required|in:collection,disposal,both',
                'estimated_duration' => 'nullable|numeric',
                'total_volume' => 'nullable|numeric',
                'disposal_site' => 'nullable|string',
                'notes' => 'nullable|string'
            ]);

            // Create single schedule for the selected client
            Schedule::create([
                'contractor_id' => Auth::id(),
                'client_id' => $validated['client_id'],
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'],
                'pickup_location' => $validated['pickup_location'],
                'pickup_address' => $validated['pickup_address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip_code' => $validated['zip_code'],
                'service_type' => $validated['service_type'],
                'status' => 'scheduled',
                'estimated_duration' => $validated['estimated_duration'] ?? null,
                'total_volume' => $validated['total_volume'] ?? null,
                'disposal_site' => $validated['disposal_site'] ?? null,
                'notes' => $validated['notes'] ?? null
            ]);

            return redirect()->route('schedules.index')->with('success', 'Schedule created successfully');
        }
        
        // Old form format (bulk schedule creation)
        $validated = $request->validate([
            'route_name' => 'required|string|max:255',
            'site_location' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_time' => 'required|date_format:H:i',
            'comments' => 'nullable|string'
        ]);

        // Parse the site_location (format: "REGION → DISTRICT")
        $locationParts = explode(' → ', $validated['site_location']);
        $region = $locationParts[0] ?? '';
        $district = $locationParts[1] ?? '';

        // Get clients at this location
        $clients = Client::where('contractor_id', Auth::id())
            ->where(function($query) use ($region, $district, $validated) {
                // Try to match region/district from the parsed location
                if (!empty($region) && !empty($district)) {
                    $query->where(function($q) use ($region, $district) {
                        $q->where('address', 'like', '%' . $region . '%')
                          ->orWhere('address', 'like', '%' . $district . '%')
                          ->orWhere('city', 'like', '%' . $district . '%')
                          ->orWhere('state', 'like', '%' . $region . '%');
                    });
                } else {
                    // Fallback to full location string match
                    $query->where('address', 'like', '%' . $validated['site_location'] . '%');
                }
            })
            ->get();

        // Create schedule for each client
        foreach ($clients as $client) {
            Schedule::create([
                'contractor_id' => Auth::id(),
                'client_id' => $client->id,
                'pickup_date' => $validated['start_date'],
                'pickup_time' => $validated['pickup_time'],
                'pickup_location' => $validated['route_name'],
                'pickup_address' => $client->address,
                'city' => $client->city,
                'state' => $client->state,
                'zip_code' => $client->zip_code,
                'service_type' => 'collection',
                'status' => 'scheduled',
                'notes' => $validated['comments']
            ]);
        }

        return redirect()->route('schedules.index')->with('success', 'Collection schedule created successfully');
    }

    public function show(Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id()) {
            abort(404);
        }

        // Get all schedules for the same location and date range
        $locationSchedules = Schedule::forContractor(Auth::id())
            ->where('pickup_location', $schedule->pickup_location)
            ->where('pickup_date', '>=', $schedule->pickup_date)
            ->with('client')
            ->orderBy('pickup_address')
            ->get();

        return view('schedules.show', compact('schedule', 'locationSchedules'));
    }

    public function updateStatus(Request $request, Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id()) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:scheduled,in_progress,completed,cancelled'
        ]);

        $oldStatus = $schedule->status;
        $schedule->update(['status' => $validated['status']]);

        // Add message when marking as completed
        $message = 'Status updated successfully';
        if ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
            $message = 'Schedule marked as completed! Please record disposal data in the Disposal section.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'redirectToDisposal' => $validated['status'] === 'completed'
        ]);
    }

    public function print(Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id()) {
            abort(404);
        }

        $locationSchedules = Schedule::forContractor(Auth::id())
            ->where('pickup_location', $schedule->pickup_location)
            ->where('pickup_date', '>=', $schedule->pickup_date)
            ->with('client')
            ->orderBy('pickup_address')
            ->get();

        return view('schedules.print', compact('schedule', 'locationSchedules'));
    }
}