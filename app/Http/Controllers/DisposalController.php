<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisposalController extends Controller
{
    public function index()
    {
        $schedules = Schedule::forContractor(Auth::id())
            ->where('status', 'completed')
            ->with('client')
            ->orderBy('pickup_date', 'desc')
            ->paginate(15);

        return view('disposal.index', compact('schedules'));
    }

    public function show(Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id()) {
            abort(404);
        }

        return view('disposal.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id()) {
            abort(404);
        }

        $dumpingSites = collect(config('dumping_sites.sites', []))->pluck('name');

        return view('disposal.edit', compact('schedule', 'dumpingSites'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id()) {
            abort(404);
        }

        $validated = $request->validate([
            'weight_kg' => 'required|numeric|min:0.1|max:100000',
            'waste_category' => 'required|in:general,organic,recyclable,mixed',
            'disposal_site' => 'required|string|max:255',
            'disposal_type' => 'required|in:sorting_facility,landfill',
            'total_volume' => 'nullable|numeric|min:0',
            'disposal_notes' => 'nullable|string'
        ]);

        $schedule->update([
            'weight_kg' => $validated['weight_kg'],
            'waste_category' => $validated['waste_category'],
            'disposal_site' => $validated['disposal_site'],
            'disposal_type' => $validated['disposal_type'],
            'total_volume' => $validated['total_volume'] ?? $schedule->total_volume,
            'disposal_notes' => $validated['disposal_notes']
        ]);

        return redirect()->route('disposal.index')->with('success', 'Disposal data recorded successfully');
    }
}