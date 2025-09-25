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
            'site_location' => 'required|string',
            'start_latitude' => 'required|numeric',
            'start_longitude' => 'required|numeric'
        ]);

        $clients = Client::where('contractor_id', Auth::id())
            ->where('address', 'like', '%' . $validated['site_location'] . '%')
            ->get();

        $optimizedRoute = $this->calculateOptimalRoute(
            $clients,
            $validated['start_latitude'],
            $validated['start_longitude']
        );

        return response()->json([
            'success' => true,
            'route' => $optimizedRoute,
            'total_distance' => $this->calculateTotalDistance($optimizedRoute),
            'estimated_time' => $this->estimateTime($optimizedRoute)
        ]);
    }

    private function calculateOptimalRoute($clients, $startLat, $startLng)
    {
        if ($clients->isEmpty()) {
            return [];
        }

        $route = [];
        $unvisited = $clients->toArray();
        $currentLat = $startLat;
        $currentLng = $startLng;

        // Simple nearest neighbor algorithm
        while (!empty($unvisited)) {
            $nearestIndex = 0;
            $minDistance = $this->calculateDistance(
                $currentLat, $currentLng,
                $unvisited[0]['latitude'], $unvisited[0]['longitude']
            );

            for ($i = 1; $i < count($unvisited); $i++) {
                $distance = $this->calculateDistance(
                    $currentLat, $currentLng,
                    $unvisited[$i]['latitude'], $unvisited[$i]['longitude']
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
                'latitude' => $nearest['latitude'],
                'longitude' => $nearest['longitude'],
                'category' => $nearest['category'],
                'phone' => $nearest['phone']
            ];

            $currentLat = $nearest['latitude'];
            $currentLng = $nearest['longitude'];
            array_splice($unvisited, $nearestIndex, 1);
        }

        return $route;
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km
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

    private function estimateTime($route)
    {
        $totalDistance = $this->calculateTotalDistance($route);
        $avgSpeed = 30; // km/h
        $stopTime = count($route) * 5; // 5 minutes per stop
        
        return round(($totalDistance / $avgSpeed * 60) + $stopTime, 0); // minutes
    }
}