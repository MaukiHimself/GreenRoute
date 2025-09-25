<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteOptimizationController extends Controller
{
    public function index()
    {
        $locations = Client::where('contractor_id', Auth::id())
            ->select('address')
            ->distinct()
            ->pluck('address');

        return view('routes.index', compact('locations'));
    }

    public function optimize(Request $request)
    {
        $validated = $request->validate([
            'site_location' => 'required|string'
        ]);

        $clients = Client::where('contractor_id', Auth::id())
            ->where('address', 'like', '%' . $validated['site_location'] . '%')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $optimizedRoute = $this->calculateOptimalRoute($clients);

        return response()->json([
            'success' => true,
            'route' => $optimizedRoute,
            'total_distance' => $this->calculateTotalDistance($optimizedRoute)
        ]);
    }

    private function calculateOptimalRoute($clients)
    {
        if ($clients->isEmpty()) {
            return [];
        }

        $route = [];
        $unvisited = $clients->toArray();
        
        // Start from the first client's GPS coordinates
        $firstClient = array_shift($unvisited);
        $route[] = [
            'id' => $firstClient['id'],
            'name' => $firstClient['name'],
            'address' => $firstClient['address'],
            'latitude' => floatval($firstClient['latitude']),
            'longitude' => floatval($firstClient['longitude']),
            'phone' => $firstClient['phone']
        ];
        
        $currentLat = floatval($firstClient['latitude']);
        $currentLng = floatval($firstClient['longitude']);

        // Find nearest neighbor for remaining clients
        while (!empty($unvisited)) {
            $nearestIndex = 0;
            $minDistance = $this->calculateDistance(
                $currentLat, $currentLng,
                floatval($unvisited[0]['latitude']), floatval($unvisited[0]['longitude'])
            );

            for ($i = 1; $i < count($unvisited); $i++) {
                $distance = $this->calculateDistance(
                    $currentLat, $currentLng,
                    floatval($unvisited[$i]['latitude']), floatval($unvisited[$i]['longitude'])
                );

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $nearestIndex = $i;
                }
            }

            $nearest = $unvisited[$nearestIndex];
            $route[] = [
                'id' => $nearest['id'],
                'name' => $nearest['name'],
                'address' => $nearest['address'],
                'latitude' => floatval($nearest['latitude']),
                'longitude' => floatval($nearest['longitude']),
                'phone' => $nearest['phone']
            ];

            $currentLat = floatval($nearest['latitude']);
            $currentLng = floatval($nearest['longitude']);
            array_splice($unvisited, $nearestIndex, 1);
        }

        return $route;
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    private function calculateTotalDistance($route)
    {
        if (count($route) < 2) return 0;

        $totalDistance = 0;
        for ($i = 0; $i < count($route) - 1; $i++) {
            $totalDistance += $this->calculateDistance(
                $route[$i]['latitude'], $route[$i]['longitude'],
                $route[$i + 1]['latitude'], $route[$i + 1]['longitude']
            );
        }

        return round($totalDistance, 2);
    }
}