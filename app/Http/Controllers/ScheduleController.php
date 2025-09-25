<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::forContractor(Auth::id())
            ->with('client')
            ->orderBy('pickup_date', 'desc')
            ->paginate(15);

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        $locations = Client::where('contractor_id', Auth::id())
            ->select('address')
            ->distinct()
            ->pluck('address');

        return view('schedules.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|max:255',
            'site_location' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_time' => 'required|date_format:H:i',
            'comments' => 'nullable|string'
        ]);

        // Get clients at this location
        $clients = Client::where('contractor_id', Auth::id())
            ->where('address', 'like', '%' . $validated['site_location'] . '%')
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

        $schedule->update(['status' => $validated['status']]);

        return response()->json(['success' => true]);
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