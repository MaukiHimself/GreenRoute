<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TruckController extends Controller
{
    public function index()
    {
        $trucks = Truck::where('contractor_id', Auth::id())->get();
        return view('gps.index', compact('trucks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20',
            'driver_name' => 'required|string|max:100',
            'driver_phone' => 'required|string|max:20',
            'truck_type' => 'required|string'
        ]);

        Truck::create([
            'contractor_id' => Auth::id(),
            'plate_number' => $validated['plate_number'],
            'driver_name' => $validated['driver_name'],
            'driver_phone' => $validated['driver_phone'],
            'truck_type' => $validated['truck_type'],
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Truck registered successfully');
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

        return response()->json(['success' => true]);
    }

    public function getLocations()
    {
        $trucks = Truck::where('contractor_id', Auth::id())
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->get();

        return response()->json($trucks);
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
}