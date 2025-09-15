<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Schedule::where('contractor_id', Auth::id())
            ->with('client')
            ->orderBy('pickup_date', 'asc')
            ->orderBy('pickup_time', 'asc')
            ->paginate(15);
            
        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('contractor_id', Auth::id())
            ->where('status', 'active')
            ->get();
        return view('schedules.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required|date_format:H:i',
            'pickup_location' => 'required|string|max:255',
            'pickup_address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
            'service_type' => 'required|in:collection,disposal,both',
            'estimated_duration' => 'nullable|numeric|min:0.25|max:24',
            'notes' => 'nullable|string'
        ]);

        // Verify client belongs to contractor
        $client = Client::where('contractor_id', Auth::id())
            ->where('id', $validated['client_id'])
            ->firstOrFail();
        
        $validated['contractor_id'] = Auth::id();
        
        Schedule::create($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        $this->authorize('view', $schedule);
        return view('schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $this->authorize('update', $schedule);
        $clients = Client::where('contractor_id', Auth::id())
            ->where('status', 'active')
            ->get();
        return view('schedules.edit', compact('schedule', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required|date_format:H:i',
            'pickup_location' => 'required|string|max:255',
            'pickup_address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
            'service_type' => 'required|in:collection,disposal,both',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'estimated_duration' => 'nullable|numeric|min:0.25|max:24',
            'notes' => 'nullable|string'
        ]);

        // Verify client belongs to contractor
        $client = Client::where('contractor_id', Auth::id())
            ->where('id', $validated['client_id'])
            ->firstOrFail();
        
        $schedule->update($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);
        
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }
}
