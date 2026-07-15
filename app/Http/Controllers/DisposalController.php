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
            'disposal_notes' => 'nullable|string'
        ]);

        $schedule->update([
            'weight_kg' => $validated['weight_kg'],
            'waste_category' => $validated['waste_category'],
            'disposal_site' => $validated['disposal_site'],
            // Recyclable/organic loads go to the sorting facility, the rest to
            // landfill — derived so reports keep their recycling split.
            'disposal_type' => in_array($validated['waste_category'], ['recyclable', 'organic'])
                ? 'sorting_facility' : 'landfill',
            'disposal_notes' => $validated['disposal_notes'],
            // The contractor filled it personally — no separate confirmation needed.
            'disposal_recorded_by' => 'contractor',
            'disposal_confirmed_at' => now(),
        ]);

        return redirect()->route('disposal.index')->with('success', 'Disposal data recorded successfully');
    }

    /**
     * Confirm a disposal record submitted by the driver from his terminal.
     */
    public function confirm(Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id()) {
            abort(404);
        }

        if (!$schedule->weight_kg) {
            return redirect()->route('disposal.index')->with('error', 'Nothing to confirm — no disposal record on this collection yet.');
        }

        $schedule->update(['disposal_confirmed_at' => now()]);

        return redirect()->route('disposal.index')->with('success', 'Driver disposal record confirmed.');
    }
}