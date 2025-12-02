<?php

namespace App\Http\Controllers;

use App\Models\ContractorRoute;
use App\Models\Client;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteManagementController extends Controller
{
    /**
     * Display a listing of routes
     */
    public function index()
    {
        $contractorId = Auth::id();
        
        $routes = ContractorRoute::where('contractor_id', $contractorId)
            ->withCount('clients')
            ->orderBy('route_name')
            ->get();
        
        return view('route-management.index', compact('routes'));
    }

    /**
     * Show the form for creating a new route
     */
    public function create()
    {
        $contractorId = Auth::id();
        
        // Get all clients and existing routes
        $clients = Client::where('contractor_id', $contractorId)
            ->orderBy('name')
            ->get();
        
        $existingRoutes = ContractorRoute::where('contractor_id', $contractorId)
            ->pluck('route_name');
        
        // Get site locations from tbl_locations grouped by Region -> District
        $siteLocations = Location::select('region', 'district')
            ->distinct()
            ->orderBy('region')
            ->orderBy('district')
            ->get()
            ->groupBy('region')
            ->map(function ($districts) {
                return $districts->pluck('district')->unique()->values();
            });
        
        return view('route-management.create', compact('clients', 'existingRoutes', 'siteLocations'));
    }

    /**
     * Store a newly created route
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'client_ids' => 'nullable|array',
            'client_ids.*' => 'exists:clients,id',
        ]);

        $contractorId = Auth::id();
        
        // Check if route name already exists for this contractor
        $exists = ContractorRoute::where('contractor_id', $contractorId)
            ->where('route_name', $validated['route_name'])
            ->exists();
        
        if ($exists) {
            return back()->withErrors(['route_name' => 'A route with this name already exists.'])->withInput();
        }

        // Create the route
        $route = ContractorRoute::create([
            'contractor_id' => $contractorId,
            'route_name' => $validated['route_name'],
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#055c5c',
            'is_active' => true,
        ]);

        // Assign clients to this route
        if (!empty($validated['client_ids'])) {
            Client::whereIn('id', $validated['client_ids'])
                ->where('contractor_id', $contractorId)
                ->update(['route' => $validated['route_name']]);
        }

        return redirect()->route('route-management.index')
            ->with('success', 'Route created successfully!');
    }

    /**
     * Display the specified route
     */
    public function show(ContractorRoute $contractorRoute)
    {
        // Ensure the route belongs to the authenticated contractor
        if ($contractorRoute->contractor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $clients = Client::where('contractor_id', Auth::id())
            ->where('route', $contractorRoute->route_name)
            ->orderBy('name')
            ->get();
        
        return view('route-management.show', compact('contractorRoute', 'clients'));
    }

    /**
     * Show the form for editing the specified route
     */
    public function edit(ContractorRoute $contractorRoute)
    {
        // Ensure the route belongs to the authenticated contractor
        if ($contractorRoute->contractor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $contractorId = Auth::id();
        
        // Get all clients
        $allClients = Client::where('contractor_id', $contractorId)
            ->orderBy('name')
            ->get();
        
        // Get clients assigned to this route
        $assignedClientIds = Client::where('contractor_id', $contractorId)
            ->where('route', $contractorRoute->route_name)
            ->pluck('id')
            ->toArray();
        
        return view('route-management.edit', compact('contractorRoute', 'allClients', 'assignedClientIds'));
    }

    /**
     * Update the specified route
     */
    public function update(Request $request, ContractorRoute $contractorRoute)
    {
        // Ensure the route belongs to the authenticated contractor
        if ($contractorRoute->contractor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'route_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'client_ids' => 'nullable|array',
            'client_ids.*' => 'exists:clients,id',
        ]);

        $contractorId = Auth::id();
        $oldRouteName = $contractorRoute->route_name;
        
        // Check if new route name conflicts with existing routes (except current one)
        if ($validated['route_name'] !== $oldRouteName) {
            $exists = ContractorRoute::where('contractor_id', $contractorId)
                ->where('route_name', $validated['route_name'])
                ->where('id', '!=', $contractorRoute->id)
                ->exists();
            
            if ($exists) {
                return back()->withErrors(['route_name' => 'A route with this name already exists.'])->withInput();
            }
        }

        // Update the route
        $contractorRoute->update([
            'route_name' => $validated['route_name'],
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#055c5c',
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // If route name changed, update all clients with old route name
        if ($validated['route_name'] !== $oldRouteName) {
            Client::where('contractor_id', $contractorId)
                ->where('route', $oldRouteName)
                ->update(['route' => $validated['route_name']]);
        }

        // Unassign all clients from this route first
        Client::where('contractor_id', $contractorId)
            ->where('route', $validated['route_name'])
            ->update(['route' => null]);

        // Reassign selected clients
        if (!empty($validated['client_ids'])) {
            Client::whereIn('id', $validated['client_ids'])
                ->where('contractor_id', $contractorId)
                ->update(['route' => $validated['route_name']]);
        }

        return redirect()->route('route-management.index')
            ->with('success', 'Route updated successfully!');
    }

    /**
     * Remove the specified route
     */
    public function destroy(ContractorRoute $contractorRoute)
    {
        // Ensure the route belongs to the authenticated contractor
        if ($contractorRoute->contractor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $contractorId = Auth::id();
        $routeName = $contractorRoute->route_name;
        
        // Unassign all clients from this route
        Client::where('contractor_id', $contractorId)
            ->where('route', $routeName)
            ->update(['route' => null]);
        
        // Delete the route
        $contractorRoute->delete();
        
        return redirect()->route('route-management.index')
            ->with('success', 'Route deleted successfully!');
    }
}
