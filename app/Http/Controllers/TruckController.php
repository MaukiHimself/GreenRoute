<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\ContractorRoute;
use App\Models\Client;
use App\Models\User;
use App\Models\Message;
use App\Models\TruckLocationHistory;
use App\Models\CollectionRun;
use App\Models\CollectionRunStop;
use App\Notifications\GenericNotification;
use App\Events\TruckLocationUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TruckController extends Controller
{
    /**
     * Default location when a contractor has no base set yet
     * (City Centre, Dar es Salaam).
     */
    private const DEFAULT_BASE = ['lat' => -6.7924, 'lng' => 39.2083];

    public function index()
    {
        $trucks = Truck::where('contractor_id', Auth::id())
            ->with('assignedRoute')
            ->get();

        $routes = ContractorRoute::where('contractor_id', Auth::id())
            ->orderBy('route_name')
            ->get();

        $user = Auth::user();
        $base = ($user->latitude && $user->longitude)
            ? ['lat' => (float) $user->latitude, 'lng' => (float) $user->longitude, 'address' => $user->location_address]
            : null;

        $truckMeta = $trucks->map(function ($t) {
            return [
                'id' => $t->id,
                'plate' => $t->plate_number,
                'assigned_route_id' => $t->assigned_route_id,
                'assigned_route_name' => $t->assignedRoute ? $t->assignedRoute->route_name : null,
                'route_color' => $t->assignedRoute ? $t->assignedRoute->color : null,
                'base_lat' => $t->base_latitude,
                'base_lng' => $t->base_longitude,
                'lat' => $t->current_latitude,
                'lng' => $t->current_longitude,
            ];
        })->values()->all();

        return view('gps.index', compact('trucks', 'routes', 'base', 'truckMeta'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20',
            'driver_name' => 'required|string|max:100',
            'driver_phone' => 'required|string|max:20',
            'truck_type' => 'required|string'
        ]);

        $base = $this->contractorBase(Auth::user());

        Truck::create([
            'contractor_id' => Auth::id(),
            'plate_number' => $validated['plate_number'],
            'driver_name' => $validated['driver_name'],
            'driver_phone' => $validated['driver_phone'],
            'truck_type' => $validated['truck_type'],
            'status' => 'active',
            'base_latitude' => $base['lat'],
            'base_longitude' => $base['lng'],
            'current_latitude' => $base['lat'],
            'current_longitude' => $base['lng'],
            'tracking_token' => \Illuminate\Support\Str::random(32)
        ]);

        return redirect()->back()->with('success', 'Truck registered successfully and parked at your base location.');
    }

    /**
     * Assign a contractor route to a truck and send it back to base.
     */
    public function assignRoute(Request $request, Truck $truck)
    {
        if ($truck->contractor_id !== Auth::id()) {
            abort(404);
        }

        $validated = $request->validate([
            'route_id' => 'nullable|exists:contractor_routes,id',
        ]);

        $routeId = $validated['route_id'] ?? null;

        if ($routeId) {
            $route = ContractorRoute::where('id', $routeId)
                ->where('contractor_id', Auth::id())
                ->firstOrFail();

            $truck->update([
                'assigned_route_id' => $route->id,
                'current_latitude' => $truck->base_latitude,
                'current_longitude' => $truck->base_longitude,
                'previous_latitude' => null,
                'previous_longitude' => null,
            ]);

            // Begin a fresh collection run (resets the stop checklist).
            $this->startRunForTruck($truck->fresh(), $route);
        } else {
            // Clearing the route ends any active run.
            $this->abandonActiveRun($truck);
            $truck->update(['assigned_route_id' => null, 'stop_statuses' => []]);
        }

        return redirect()->back()->with(
            'success',
            $routeId ? 'Route assigned. Truck is now parked at base, ready to follow the route.' : 'Route cleared from truck.'
        );
    }

    public function updateLocation(Request $request, Truck $truck)
    {
        if ($truck->contractor_id !== Auth::id()) {
            abort(404);
        }

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        $truck->update([
            'current_latitude' => $validated['latitude'],
            'current_longitude' => $validated['longitude'],
            'last_updated' => now()
        ]);

        // Calculate distance if previous location exists
        if ($truck->previous_latitude && $truck->previous_longitude) {
            $distance = $this->calculateDistance(
                $truck->previous_latitude, $truck->previous_longitude,
                $validated['latitude'], $validated['longitude']
            );

            $truck->increment('daily_distance', $distance);
        }

        $truck->update([
            'previous_latitude' => $validated['latitude'],
            'previous_longitude' => $validated['longitude']
        ]);

        // Log history, calculate ETAs, send SMS alerts if nearby, and broadcast real-time update
        $etaList = $this->logLocationAndCalculateEtas($truck, $validated['latitude'], $validated['longitude']);

        event(new TruckLocationUpdated(
            $truck,
            $validated['latitude'],
            $validated['longitude'],
            $truck->daily_distance,
            true, // is_online
            $truck->stop_statuses ?? [],
            $etaList
        ));

        return response()->json(['success' => true]);
    }

    public function getLocations()
    {
        $trucks = Truck::where('contractor_id', Auth::id())
            ->with('assignedRoute')
            ->get();

        $trucks->each(function ($truck) {
            $truck->is_online = $truck->last_updated && $truck->last_updated->diffInMinutes(now()) < 10;
            $truck->assigned_route_name = $truck->assignedRoute?->route_name;

            if ($truck->assignedRoute) {
                $clientsQuery = Client::where('contractor_id', $truck->contractor_id)
                    ->where('route', $truck->assignedRoute->route_name)
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude');

                $totalStops = $clientsQuery->count();
                $clientIds = $clientsQuery->pluck('id')->toArray();

                $stopStatuses = $truck->stop_statuses ?? [];
                $completedStops = 0;
                foreach ($clientIds as $cid) {
                    if (isset($stopStatuses[$cid]) && in_array($stopStatuses[$cid], ['collected', 'skipped', 'blocked'])) {
                        $completedStops++;
                    }
                }

                $truck->total_stops = $totalStops;
                $truck->completed_stops = $completedStops;
                $truck->progress_percent = ($totalStops > 0) ? round(($completedStops / $totalStops) * 100) : 0;
            } else {
                $truck->total_stops = 0;
                $truck->completed_stops = 0;
                $truck->progress_percent = 0;
            }
        });

        return response()->json($trucks);
    }

    /**
     * Return the ordered waypoints of a truck's assigned route so the map can
     * draw & animate it: contractor base -> clients -> dumping site.
     */
    public function routePath(Truck $truck)
    {
        if ($truck->contractor_id !== Auth::id()) {
            abort(404);
        }

        if (!$truck->assigned_route_id) {
            return response()->json(['success' => false, 'message' => 'No route assigned to this truck.']);
        }

        $waypoints = $this->buildRouteWaypoints($truck);

        return response()->json([
            'success' => true,
            'truck_id' => $truck->id,
            'route_name' => $truck->assignedRoute->route_name,
            'route_color' => $truck->assignedRoute->color,
            'base' => ['lat' => (float) $truck->base_latitude, 'lng' => (float) $truck->base_longitude],
            'waypoints' => $waypoints,
            'geometry' => $this->roadGeometry($waypoints),
            'current' => [
                'lat' => $truck->current_latitude ? (float) $truck->current_latitude : null,
                'lng' => $truck->current_longitude ? (float) $truck->current_longitude : null,
            ],
            'stop_statuses' => $truck->stop_statuses ?? new \stdClass()
        ]);
    }

    /**
     * Resolve the road-following geometry for an ordered set of waypoints.
     *
     * Computed server-side (where the routing engines are reliably reachable)
     * and cached, so the map never depends on flaky per-client browser calls.
     * Returns an array of [lat, lng] pairs, or null if routing is unavailable.
     */
    private function roadGeometry(array $waypoints): ?array
    {
        $points = array_values(array_filter($waypoints, function ($wp) {
            return isset($wp['lat'], $wp['lng']) && is_numeric($wp['lat']) && is_numeric($wp['lng']);
        }));

        if (count($points) < 2) {
            return null;
        }

        // Cache per exact coordinate list so route/stop changes recompute.
        $coordKey = collect($points)
            ->map(fn ($p) => round($p['lat'], 6) . ',' . round($p['lng'], 6))
            ->implode(';');
        $cacheKey = 'road_geom:' . md5($coordKey);

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($points) {
            // 1) OpenRouteService (uses the configured HeiGIT key).
            $orsKey = config('services.heigit.api_key');
            if ($orsKey) {
                try {
                    $resp = Http::timeout(15)
                        ->withHeaders(['Authorization' => $orsKey])
                        ->post('https://api.openrouteservice.org/v2/directions/driving-car/geojson', [
                            'coordinates' => array_map(fn ($p) => [(float) $p['lng'], (float) $p['lat']], $points),
                        ]);

                    if ($resp->successful()) {
                        $coords = $resp->json('features.0.geometry.coordinates');
                        if (is_array($coords) && count($coords) > 1) {
                            return array_map(fn ($c) => [$c[1], $c[0]], $coords);
                        }
                    } else {
                        Log::warning('ORS routing failed (' . $resp->status() . '), trying OSRM.');
                    }
                } catch (\Throwable $e) {
                    Log::warning('ORS routing error: ' . $e->getMessage());
                }
            }

            // 2) OSRM public demo server fallback.
            try {
                $coordStr = collect($points)->map(fn ($p) => $p['lng'] . ',' . $p['lat'])->implode(';');
                $resp = Http::timeout(15)->get(
                    "https://router.project-osrm.org/route/v1/driving/{$coordStr}",
                    ['overview' => 'full', 'geometries' => 'geojson']
                );

                if ($resp->successful() && $resp->json('code') === 'Ok') {
                    $coords = $resp->json('routes.0.geometry.coordinates');
                    if (is_array($coords) && count($coords) > 1) {
                        return array_map(fn ($c) => [$c[1], $c[0]], $coords);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('OSRM routing error: ' . $e->getMessage());
            }

            return null;
        });
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lat1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    /**
     * Build ordered waypoints: [base, ...nearest-neighbour clients, dumping site].
     */
    private function buildRouteWaypoints(Truck $truck): array
    {
        $route = $truck->assignedRoute;

        $base = $this->resolveTruckBase($truck);

        $waypoints = [[
            'type' => 'base',
            'name' => 'Contractor base',
            'lat' => $base['lat'],
            'lng' => $base['lng'],
        ]];

        // Clients assigned to this route (by route_name).
        $clients = Client::where('contractor_id', $truck->contractor_id)
            ->where('route', $route->route_name)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $stops = $clients->map(function ($client) {
            return [
                'type' => 'client',
                'id' => $client->id,
                'name' => $client->name,
                'lat' => (float) $client->latitude,
                'lng' => (float) $client->longitude,
            ];
        })->toArray();

        // Optimise client order with nearest-neighbour, seeded from base.
        $orderedStops = $this->optimizeOrder($stops, $base);

        foreach ($orderedStops as $stop) {
            $waypoints[] = $stop;
        }

        // Dumping site (end).
        if ($route->dumping_site) {
            $site = collect(config('dumping_sites.sites', []))
                ->firstWhere('name', $route->dumping_site);

            if ($site) {
                $waypoints[] = [
                    'type' => 'dumping',
                    'name' => $site['name'],
                    'lat' => (float) $site['latitude'],
                    'lng' => (float) $site['longitude'],
                ];
            }
        }

        return $waypoints;
    }

    /**
     * Resolve a valid base (yard) coordinate for a truck. Older trucks were
     * created before the base columns existed and have NULL/zero coordinates,
     * which would place the yard at [0,0] (the ocean) and break routing — so we
     * fall back to the contractor's saved location, then the default Dar base.
     * Self-heals by persisting the resolved base back onto the truck.
     */
    private function resolveTruckBase(Truck $truck): array
    {
        $lat = $truck->base_latitude;
        $lng = $truck->base_longitude;

        $invalid = !is_numeric($lat) || !is_numeric($lng)
            || ((float) $lat === 0.0 && (float) $lng === 0.0);

        if ($invalid) {
            $contractor = $truck->contractor;
            if ($contractor && $contractor->latitude && $contractor->longitude) {
                $lat = (float) $contractor->latitude;
                $lng = (float) $contractor->longitude;
            } else {
                $lat = self::DEFAULT_BASE['lat'];
                $lng = self::DEFAULT_BASE['lng'];
            }

            // Persist so the truck marker + future routing use the fixed base.
            $truck->forceFill([
                'base_latitude' => $lat,
                'base_longitude' => $lng,
            ])->saveQuietly();
        }

        return ['lat' => (float) $lat, 'lng' => (float) $lng];
    }

    /**
     * Order client stops with a nearest-neighbour heuristic from a start point.
     */
    private function optimizeOrder(array $stops, array $start): array
    {
        $valid = array_values(array_filter($stops, function ($s) {
            return is_numeric($s['lat']) && is_numeric($s['lng']);
        }));

        if (count($valid) <= 1) {
            return $valid;
        }

        $ordered = [];
        $remaining = $valid;
        $current = $start;

        while (!empty($remaining)) {
            $bestIdx = 0;
            $bestDist = $this->haversine($current['lat'], $current['lng'], $remaining[0]['lat'], $remaining[0]['lng']);

            foreach ($remaining as $i => $stop) {
                $d = $this->haversine($current['lat'], $current['lng'], $stop['lat'], $stop['lng']);
                if ($d < $bestDist) {
                    $bestDist = $d;
                    $bestIdx = $i;
                }
            }

            $next = $remaining[$bestIdx];
            $ordered[] = $next;
            $current = ['lat' => $next['lat'], 'lng' => $next['lng']];
            unset($remaining[$bestIdx]);
            $remaining = array_values($remaining);
        }

        return $ordered;
    }

    private function haversine($lat1, $lng1, $lat2, $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    private function contractorBase(User $user): array
    {
        if ($user->latitude && $user->longitude) {
            return ['lat' => (float) $user->latitude, 'lng' => (float) $user->longitude];
        }

        return self::DEFAULT_BASE;
    }

    public function driverTrack($token)
    {
        $truck = Truck::where('tracking_token', $token)->firstOrFail();
        
        $waypoints = [];
        $etaList = [];
        $geometry = null;

        if ($truck->assignedRoute) {
            $waypoints = $this->buildRouteWaypoints($truck);
            $geometry = $this->roadGeometry($waypoints);

            if ($truck->current_latitude && $truck->current_longitude) {
                $etaList = $this->calculateEtas($truck, $truck->current_latitude, $truck->current_longitude);
            }
        }

        // Other active routes the driver can switch to once the current run finishes.
        $availableRoutes = ContractorRoute::where('contractor_id', $truck->contractor_id)
            ->where('is_active', true)
            ->orderBy('route_name')
            ->get(['id', 'route_name', 'color']);

        return view('driver.track', compact('truck', 'waypoints', 'etaList', 'availableRoutes', 'geometry'));
    }

    public function updateLocationByToken(Request $request, $token)
    {
        $truck = Truck::where('tracking_token', $token)->firstOrFail();

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        $truck->update([
            'current_latitude' => $validated['latitude'],
            'current_longitude' => $validated['longitude'],
            'last_updated' => now()
        ]);

        // Calculate distance if previous location exists
        if ($truck->previous_latitude && $truck->previous_longitude) {
            $distance = $this->calculateDistance(
                $truck->previous_latitude, $truck->previous_longitude,
                $validated['latitude'], $validated['longitude']
            );

            $truck->increment('daily_distance', $distance);
        }

        $truck->update([
            'previous_latitude' => $validated['latitude'],
            'previous_longitude' => $validated['longitude']
        ]);

        // Log history, calculate ETAs, send SMS alerts if nearby, and broadcast real-time update
        $etaList = $this->logLocationAndCalculateEtas($truck, $validated['latitude'], $validated['longitude']);

        event(new TruckLocationUpdated(
            $truck,
            $validated['latitude'],
            $validated['longitude'],
            $truck->daily_distance,
            true, // is_online
            $truck->stop_statuses ?? [],
            $etaList
        ));

        return response()->json([
            'success' => true,
            'stop_statuses' => $truck->stop_statuses ?? [],
            'eta_list' => $etaList
        ]);
    }

    public function updateStopStatus(Request $request, $token)
    {
        $truck = Truck::where('tracking_token', $token)->firstOrFail();

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|string|in:pending,collected,skipped,blocked',
        ]);

        $statuses = $truck->stop_statuses ?? [];
        $statuses[$validated['client_id']] = $validated['status'];
        
        $truck->update([
            'stop_statuses' => $statuses
        ]);

        $client = Client::find($validated['client_id']);

        // Record this action against the active collection run (audit trail).
        $this->recordRunStop($truck, $client, $validated['status']);

        if ($client) {
            $statusLabel = ucfirst($validated['status']);
            $messageText = "";
            if ($validated['status'] === 'collected') {
                $messageText = "GreenRoute: Waste collected successfully from your premises at " . now()->format('H:i') . ". Thank you for keeping Dar es Salaam clean!";
            } elseif ($validated['status'] === 'skipped') {
                $messageText = "GreenRoute Notice: Your waste collection stop was skipped because no waste was put out for collection.";
            } elseif ($validated['status'] === 'blocked') {
                $messageText = "GreenRoute Alert: We could not collect waste from your premises due to an access issue (locked gate or blocked road).";
            }

            if ($messageText) {
                // Log and save Message
                Message::create([
                    'contractor_id' => $truck->contractor_id,
                    'client_id' => $client->id,
                    'sender_type' => 'contractor',
                    'message' => $messageText,
                    'message_type' => 'collection_update',
                    'status' => 'sent'
                ]);
                \Log::info("SMS to {$client->phone}: {$messageText}");

                if ($client->user) {
                    $client->user->notify(new GenericNotification(
                        title: 'Collection Update',
                        message: $statusLabel . ': ' . $client->name,
                        url: route('client.dashboard'),
                        icon: $validated['status'] === 'collected' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill',
                    ));
                }
            }
        }

        // Finalize the run + alert the contractor if every stop is now actioned.
        $completedRun = $this->finalizeRunIfComplete($truck);

        // Calculate current ETAs after stop status change
        $etaList = [];
        if ($truck->current_latitude && $truck->current_longitude) {
            $etaList = $this->calculateEtas($truck, $truck->current_latitude, $truck->current_longitude);
        }

        // Broadcast the update so the contractor map updates immediately
        event(new TruckLocationUpdated(
            $truck,
            $truck->current_latitude,
            $truck->current_longitude,
            $truck->daily_distance,
            true,
            $statuses,
            $etaList
        ));

        return response()->json([
            'success' => true,
            'stop_statuses' => $statuses,
            'eta_list' => $etaList,
            'route_complete' => (bool) $completedRun,
            'run_summary' => $completedRun ? [
                'route_name' => $completedRun->route_name,
                'total_stops' => $completedRun->total_stops,
                'collected' => $completedRun->collected_count,
                'skipped' => $completedRun->skipped_count,
                'blocked' => $completedRun->blocked_count,
            ] : null,
        ]);
    }

    /**
     * Public driver action: switch the truck to another of the contractor's
     * routes and begin a fresh collection run (used after finishing a route).
     */
    public function startRouteByToken(Request $request, $token)
    {
        $truck = Truck::where('tracking_token', $token)->firstOrFail();

        $validated = $request->validate([
            'route_id' => 'required|exists:contractor_routes,id',
        ]);

        $route = ContractorRoute::where('id', $validated['route_id'])
            ->where('contractor_id', $truck->contractor_id)
            ->firstOrFail();

        $truck->update([
            'assigned_route_id' => $route->id,
            'current_latitude' => $truck->base_latitude,
            'current_longitude' => $truck->base_longitude,
            'previous_latitude' => null,
            'previous_longitude' => null,
        ]);

        $truck = $truck->fresh();
        $this->startRunForTruck($truck, $route);

        $truck = $truck->fresh();
        $waypoints = $this->buildRouteWaypoints($truck);
        $etaList = ($truck->current_latitude && $truck->current_longitude)
            ? $this->calculateEtas($truck, $truck->current_latitude, $truck->current_longitude)
            : [];

        return response()->json([
            'success' => true,
            'route_name' => $route->route_name,
            'waypoints' => $waypoints,
            'geometry' => $this->roadGeometry($waypoints),
            'stop_statuses' => (object) [],
            'eta_list' => $etaList,
        ]);
    }

    /**
     * Contractor-facing collection history: recent finished/abandoned runs with
     * their per-client stop breakdown. Feeds the Collection History drawer.
     */
    public function collectionRuns()
    {
        $runs = CollectionRun::where('contractor_id', Auth::id())
            ->whereIn('status', ['completed', 'abandoned'])
            ->with(['truck:id,plate_number,driver_name', 'stops'])
            ->orderByDesc('completed_at')
            ->orderByDesc('id')
            ->limit(30)
            ->get();

        $payload = $runs->map(function ($run) {
            return [
                'id' => $run->id,
                'route_name' => $run->route_name,
                'status' => $run->status,
                'plate' => $run->truck?->plate_number,
                'driver' => $run->truck?->driver_name,
                'started_at' => $run->started_at?->toIso8601String(),
                'completed_at' => $run->completed_at?->toIso8601String(),
                'total_stops' => $run->total_stops,
                'collected' => $run->collected_count,
                'skipped' => $run->skipped_count,
                'blocked' => $run->blocked_count,
                'stops' => $run->stops->map(fn ($s) => [
                    'client_name' => $s->client_name,
                    'status' => $s->status,
                    'actioned_at' => $s->actioned_at?->toIso8601String(),
                ])->values(),
            ];
        });

        return response()->json(['success' => true, 'runs' => $payload]);
    }

    public function getPlaybackHistory(Request $request, Truck $truck)
    {
        if ($truck->contractor_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d'
        ]);

        $date = $validated['date'];

        $history = TruckLocationHistory::where('truck_id', $truck->id)
            ->whereDate('recorded_at', $date)
            ->orderBy('recorded_at', 'asc')
            ->get(['latitude', 'longitude', 'recorded_at']);

        return response()->json([
            'success' => true,
            'date' => $date,
            'history' => $history
        ]);
    }

    public function destroy(Truck $truck)
    {
        if ($truck->contractor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $truck->delete();

        return redirect()->back()->with('success', 'Truck removed successfully');
    }

    /**
     * Clients on this truck's assigned route that have coordinates (the stops).
     */
    private function routeStopClients(Truck $truck)
    {
        if (!$truck->assignedRoute) {
            return collect();
        }

        return Client::where('contractor_id', $truck->contractor_id)
            ->where('route', $truck->assignedRoute->route_name)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
    }

    /**
     * Begin a new collection run for the truck on the given route: abandon any
     * still-open run and clear the stop checklist.
     */
    private function startRunForTruck(Truck $truck, ContractorRoute $route): void
    {
        $this->abandonActiveRun($truck);

        $truck->update(['stop_statuses' => []]);

        $totalStops = Client::where('contractor_id', $truck->contractor_id)
            ->where('route', $route->route_name)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->count();

        CollectionRun::create([
            'truck_id' => $truck->id,
            'contractor_id' => $truck->contractor_id,
            'route_id' => $route->id,
            'route_name' => $route->route_name,
            'started_at' => now(),
            'total_stops' => $totalStops,
            'status' => 'in_progress',
        ]);
    }

    private function abandonActiveRun(Truck $truck): void
    {
        $run = $truck->activeRun();
        if ($run) {
            $run->update(['status' => 'abandoned', 'completed_at' => now()]);
        }
    }

    /**
     * Return the active run, or lazily create one (without touching the current
     * stop checklist) for trucks whose route was assigned before runs existed.
     */
    private function ensureActiveRun(Truck $truck): ?CollectionRun
    {
        $run = $truck->activeRun();
        if ($run || !$truck->assignedRoute) {
            return $run;
        }

        return CollectionRun::create([
            'truck_id' => $truck->id,
            'contractor_id' => $truck->contractor_id,
            'route_id' => $truck->assigned_route_id,
            'route_name' => $truck->assignedRoute->route_name,
            'started_at' => now(),
            'total_stops' => $this->routeStopClients($truck)->count(),
            'status' => 'in_progress',
        ]);
    }

    /**
     * Upsert a per-client stop row on the truck's active run.
     */
    private function recordRunStop(Truck $truck, ?Client $client, string $status): void
    {
        if (!$client || !in_array($status, ['collected', 'skipped', 'blocked'], true)) {
            return;
        }

        $run = $this->ensureActiveRun($truck);
        if (!$run) {
            return;
        }

        CollectionRunStop::updateOrCreate(
            ['collection_run_id' => $run->id, 'client_id' => $client->id],
            ['client_name' => $client->name, 'status' => $status, 'actioned_at' => now()]
        );
    }

    /**
     * If every stop on the route is actioned, finalize the active run, tally the
     * counts and alert the contractor. Returns the run only when it just completed.
     */
    private function finalizeRunIfComplete(Truck $truck): ?CollectionRun
    {
        $run = $this->ensureActiveRun($truck);
        if (!$run) {
            return null;
        }

        $clients = $this->routeStopClients($truck);
        if ($clients->isEmpty()) {
            return null;
        }

        $statuses = $truck->stop_statuses ?? [];
        $done = ['collected', 'skipped', 'blocked'];

        $collected = 0;
        $skipped = 0;
        $blocked = 0;
        $actioned = 0;

        foreach ($clients as $client) {
            $st = $statuses[$client->id] ?? 'pending';
            if (!in_array($st, $done, true)) {
                continue;
            }
            $actioned++;
            if ($st === 'collected') {
                $collected++;
            } elseif ($st === 'skipped') {
                $skipped++;
            } elseif ($st === 'blocked') {
                $blocked++;
            }
        }

        // Not every stop is done yet.
        if ($actioned < $clients->count()) {
            return null;
        }

        $run->update([
            'status' => 'completed',
            'completed_at' => now(),
            'total_stops' => $clients->count(),
            'collected_count' => $collected,
            'skipped_count' => $skipped,
            'blocked_count' => $blocked,
        ]);

        // Alert the contractor's notification bell.
        $contractor = $truck->contractor;
        if ($contractor) {
            $contractor->notify(new GenericNotification(
                title: 'Route completed',
                message: "{$truck->plate_number} finished {$run->route_name}: {$collected} collected · {$skipped} skipped · {$blocked} blocked.",
                url: route('trucks.index'),
                icon: 'bi-flag-checkered',
            ));
        }

        return $run->fresh();
    }

    private function calculateEtas(Truck $truck, $latitude, $longitude): array
    {
        $route = $truck->assignedRoute;
        $etaList = [];
        if ($route) {
            $waypoints = $this->buildRouteWaypoints($truck);
            $stopStatuses = $truck->stop_statuses ?? [];
            
            $currentLat = (double) $latitude;
            $currentLng = (double) $longitude;
            $cumulativeDist = 0;
            
            foreach ($waypoints as $wp) {
                if ($wp['type'] === 'client') {
                    $client = Client::find($wp['id'] ?? null);
                    if ($client) {
                        $status = $stopStatuses[$client->id] ?? 'pending';
                        if ($status !== 'pending' && $status !== 'none') {
                            continue; // Skip already processed stops
                        }
                        
                        $dist = $this->haversine($currentLat, $currentLng, $wp['lat'], $wp['lng']);
                        $cumulativeDist += $dist;
                        
                        // 30 km/h = 2 minutes per km
                        $etaMinutes = (int) round($cumulativeDist * 2);
                        if ($etaMinutes < 1) $etaMinutes = 1;
                        
                        $etaList[] = [
                            'client_id' => $client->id,
                            'name' => $client->name,
                            'distance' => round($cumulativeDist, 2),
                            'eta_minutes' => $etaMinutes
                        ];
                        
                        $currentLat = $wp['lat'];
                        $currentLng = $wp['lng'];
                    }
                } elseif ($wp['type'] === 'dumping') {
                    $dist = $this->haversine($currentLat, $currentLng, $wp['lat'], $wp['lng']);
                    $cumulativeDist += $dist;
                    
                    $etaMinutes = (int) round($cumulativeDist * 2);
                    if ($etaMinutes < 1) $etaMinutes = 1;
                    
                    $etaList[] = [
                        'type' => 'dumping',
                        'name' => $wp['name'],
                        'distance' => round($cumulativeDist, 2),
                        'eta_minutes' => $etaMinutes
                    ];
                }
            }
        }
        return $etaList;
    }

    private function logLocationAndCalculateEtas(Truck $truck, $latitude, $longitude): array
    {
        // 1. Log coordinates to history
        TruckLocationHistory::create([
            'truck_id' => $truck->id,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'recorded_at' => now()
        ]);

        // 2. Calculate ETAs
        $etaList = $this->calculateEtas($truck, $latitude, $longitude);

        // 3. Dispatch SMS alert if first pending stop is <= 10 mins
        $pendingClients = array_filter($etaList, function($eta) {
            return isset($eta['client_id']);
        });

        if (!empty($pendingClients)) {
            $firstPending = array_values($pendingClients)[0];
            if ($firstPending['eta_minutes'] <= 10) {
                $client = Client::find($firstPending['client_id']);
                if ($client) {
                    // Check if already sent in last 2 hours
                    $alreadySent = Message::where('client_id', $client->id)
                        ->where('message_type', 'eta_alert')
                        ->where('created_at', '>=', now()->subHours(2))
                        ->exists();

                    if (!$alreadySent) {
                        $messageContent = "GreenRoute: Our waste collection truck (Plate: {$truck->plate_number}) is approximately {$firstPending['eta_minutes']} minutes away from your location. Please ensure your bin is placed outside.";
                        
                        Message::create([
                            'contractor_id' => $truck->contractor_id,
                            'client_id' => $client->id,
                            'sender_type' => 'contractor',
                            'message' => $messageContent,
                            'message_type' => 'eta_alert',
                            'status' => 'sent'
                        ]);
                        
                        \Log::info("SMS to {$client->phone}: {$messageContent}");

                        if ($client->user) {
                            $client->user->notify(new GenericNotification(
                                title: 'Collection Truck Nearby',
                                message: "The collection truck is approximately {$firstPending['eta_minutes']} minutes away.",
                                url: route('client.dashboard'),
                                icon: 'bi-truck',
                            ));
                        }
                    }
                }
            }
        }

        return $etaList;
    }
}
