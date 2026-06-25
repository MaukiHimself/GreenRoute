<?php

namespace App\Http\Controllers;

use App\Models\BillingRate;
use App\Models\ContractorBillingRateChange;
use App\Models\Schedule;
use App\Models\Client;
use App\Models\Location;
use App\Models\ContractorRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::forContractor(Auth::id())
            ->whereIn('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])
            ->with(['client', 'billingRate'])
            ->orderBy('pickup_date', 'desc')
            ->paginate(15);

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        if (Auth::user()->user_type === 'contractor') {
            $contractor = Auth::user();
            $clients = Client::where('contractor_id', Auth::id())
                ->select('id', 'name', 'registration_number', 'email', 'phone', 'address', 'city', 'region', 'district', 'ward', 'street', 'route')
                ->get();

            $regions = collect([]);
            if (Schema::hasTable('tbl_locations')) {
                try {
                    $regions = Location::select('region')
                        ->distinct()
                        ->orderBy('region')
                        ->pluck('region');
                } catch (\Exception $e) {
                    $regions = collect([]);
                }
            }

            $routes = ContractorRoute::where('contractor_id', Auth::id())
                ->where('is_active', true)
                ->whereNotNull('region')
                ->select('id', 'route_name', 'region', 'district', 'ward', 'street')
                ->orderBy('route_name')
                ->get();

            $billingRates = BillingRate::where('is_active', true)
                ->orderBy('category')
                ->orderBy('location')
                ->orderBy('frequency')
                ->get();

            $billingRatesData = $billingRates->map(function($rate) {
                return [
                    'id' => $rate->id,
                    'fee' => (float) $rate->collection_fee,
                    'label' => $rate->label,
                ];
            })->all();

            $siteLocations = collect([]);
            $assignedClient = $clients->first();

            return view('contractor.create-schedule', compact('contractor', 'clients', 'assignedClient', 'regions', 'routes', 'siteLocations', 'billingRates', 'billingRatesData'));
        }

        $regions = collect([]);
        if (Schema::hasTable('tbl_locations')) {
            try {
                $regions = Location::select('region')->distinct()->orderBy('region')->pluck('region');
            } catch (\Exception $e) {
                $regions = collect([]);
            }
        }
        return view('schedules.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'exists:clients,id',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'pickup_location' => 'required|string',
            'service_type' => 'required|string',
            'estimated_duration' => 'nullable|numeric',
            'total_volume' => 'nullable|numeric',
            'disposal_site' => 'nullable|string',
            'notes' => 'nullable|string',
            'site_location' => 'nullable|string',
            'billing_rate_id' => 'nullable|exists:billing_rates,id',
            'contractor_adjusted_fee' => 'nullable|numeric|min:0',
            'billing_rate_change_reason' => 'nullable|string|max:1000',
        ]);

        $contractor = Auth::user();
        $billingRate = $this->resolveBillingRate($validated['billing_rate_id'] ?? null);
        $contractorAdjustedFee = $this->normalizeFee($request->contractor_adjusted_fee);
        $schedulePrice = $contractorAdjustedFee ?? optional($billingRate)->collection_fee;
        $reason = $validated['billing_rate_change_reason'] ?? null;

        DB::transaction(function () use ($validated, $contractor, $billingRate, $contractorAdjustedFee, $schedulePrice, $reason) {
            foreach ($validated['client_ids'] as $clientId) {
                $client = Client::where('id', $clientId)
                    ->where('contractor_id', $contractor->id)
                    ->firstOrFail();

                $schedule = Schedule::create([
                    'contractor_id' => $contractor->id,
                    'client_id' => $client->id,
                    'contractor_registration_number' => $contractor->registration_number,
                    'client_registration_number' => $client->registration_number,
                    'route' => $client->route ?? 'Not Assigned',
                    'billing_rate_id' => $billingRate?->id,
                    'billing_rate_category' => $billingRate?->category,
                    'billing_rate_location' => $billingRate?->location,
                    'billing_rate_frequency' => $billingRate?->frequency,
                    'base_collection_fee' => $billingRate?->collection_fee,
                    'contractor_adjusted_fee' => $contractorAdjustedFee,
                    'schedule_price' => $schedulePrice,
                    'billing_rate_change_reason' => $reason,
                    'billing_rate_modified_at' => $billingRate || $contractorAdjustedFee !== null ? now() : null,
                    'pickup_date' => $validated['pickup_date'],
                    'pickup_time' => $validated['pickup_time'],
                    'scheduled_date' => $validated['pickup_date'],
                    'scheduled_time' => $validated['pickup_time'],
                    'pickup_location' => $validated['pickup_location'],
                    'pickup_address' => $client->address ?? 'Not Provided',
                    'city' => $client->city ?? 'Not Provided',
                    'state' => $client->state ?? 'Not Provided',
                    'zip_code' => $client->zip_code ?? '00000',
                    'service_type' => $validated['service_type'],
                    'status' => 'scheduled',
                    'estimated_duration' => $validated['estimated_duration'] ?? null,
                    'total_volume' => $validated['total_volume'] ?? null,
                    'disposal_site' => $validated['disposal_site'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);

                if ($billingRate || $contractorAdjustedFee !== null) {
                    $this->recordBillingRateChange(
                        $schedule,
                        $client,
                        null,
                        $billingRate,
                        null,
                        $schedulePrice,
                        $billingRate ? 'created' : 'price_added',
                        $reason
                    );
                }
            }
        });

        return redirect()->route('schedules.index')
            ->with('success', 'Schedules created successfully.');
    }

    public function show(Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id()) {
            abort(404);
        }

        $locationSchedules = Schedule::forContractor(Auth::id())
            ->where('pickup_location', $schedule->pickup_location)
            ->where('pickup_date', '>=', $schedule->pickup_date)
            ->with(['client', 'billingRate'])
            ->orderBy('pickup_address')
            ->get();

        return view('schedules.show', compact('schedule', 'locationSchedules'));
    }

    public function edit(Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id()) {
            abort(404);
        }

        $clients = Client::where('contractor_id', Auth::id())
            ->orderBy('name')
            ->get();

        $billingRates = BillingRate::where('is_active', true)
            ->orderBy('category')
            ->orderBy('location')
            ->orderBy('frequency')
            ->get();

        return view('schedules.edit', compact('schedule', 'clients', 'billingRates'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id()) {
            abort(404);
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'pickup_location' => 'required|string',
            'pickup_address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
            'service_type' => 'required|string',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'estimated_duration' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'billing_rate_id' => 'nullable|exists:billing_rates,id',
            'contractor_adjusted_fee' => 'nullable|numeric|min:0',
            'billing_rate_change_reason' => 'nullable|string|max:1000',
        ]);

        $client = Client::where('id', $validated['client_id'])
            ->where('contractor_id', Auth::id())
            ->firstOrFail();

        $oldBillingRate = $schedule->billingRate;
        $oldAdjustedFee = $this->normalizeFee($schedule->contractor_adjusted_fee);
        $oldBaseFee = $this->normalizeFee($schedule->base_collection_fee);
        $oldFee = $oldAdjustedFee ?? $oldBaseFee;

        $billingRate = $this->resolveBillingRate($validated['billing_rate_id'] ?? null);
        $contractorAdjustedFee = $this->normalizeFee($request->contractor_adjusted_fee);
        $schedulePrice = $contractorAdjustedFee ?? optional($billingRate)->collection_fee;
        $reason = $validated['billing_rate_change_reason'] ?? null;

        $hasBillingChange = $schedule->billing_rate_id !== ($billingRate?->id)
            || $this->feeValue($oldFee) !== $this->feeValue($schedulePrice)
            || $schedule->billing_rate_change_reason !== $reason;

        $validated = array_merge($validated, [
            'contractor_id' => Auth::id(),
            'client_id' => $client->id,
            'client_registration_number' => $client->registration_number,
            'route' => $client->route ?? $schedule->route,
            'billing_rate_id' => $billingRate?->id,
            'billing_rate_category' => $billingRate?->category,
            'billing_rate_location' => $billingRate?->location,
            'billing_rate_frequency' => $billingRate?->frequency,
            'base_collection_fee' => $billingRate?->collection_fee,
            'contractor_adjusted_fee' => $contractorAdjustedFee,
            'schedule_price' => $schedulePrice,
            'billing_rate_change_reason' => $reason,
            'billing_rate_modified_at' => $hasBillingChange ? now() : $schedule->billing_rate_modified_at,
            'scheduled_date' => $validated['pickup_date'],
            'scheduled_time' => $validated['pickup_time'],
        ]);

        $schedule->update($validated);

        if ($hasBillingChange) {
            $newFee = $contractorAdjustedFee ?? optional($billingRate)->collection_fee;
            $action = $this->billingChangeAction($oldBillingRate, $billingRate, $oldFee, $newFee, $schedule->billing_rate_change_reason, $reason);
            $this->recordBillingRateChange(
                $schedule,
                $client,
                $oldBillingRate,
                $billingRate,
                $oldFee,
                $newFee,
                $action,
                $reason
            );
        }

        return redirect()->route('schedules.show', $schedule)
            ->with('success', 'Schedule updated successfully.');
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
            ->with(['client', 'billingRate'])
            ->orderBy('pickup_address')
            ->get();

        return view('schedules.print', compact('schedule', 'locationSchedules'));
    }

    private function resolveBillingRate(?int $billingRateId): ?BillingRate
    {
        if (!$billingRateId) {
            return null;
        }

        $billingRate = BillingRate::where('id', $billingRateId)
            ->where('is_active', true)
            ->first();

        abort_unless($billingRate, 422, 'Selected billing rate is not active.');

        return $billingRate;
    }

    private function normalizeFee($fee): ?float
    {
        if ($fee === null || $fee === '') {
            return null;
        }

        return (float) $fee;
    }

    private function feeValue($fee): ?float
    {
        if ($fee === null || $fee === '') {
            return null;
        }

        return (float) $fee;
    }

    private function billingChangeAction(?BillingRate $oldRate, ?BillingRate $newRate, ?float $oldFee, ?float $newFee, ?string $oldReason, ?string $newReason): string
    {
        if ($oldRate && !$newRate) {
            return 'removed_rate';
        }

        if (!$oldRate && $newRate) {
            return 'selected_rate';
        }

        if ($oldRate && $newRate && $oldRate->id !== $newRate->id) {
            return 'changed_rate';
        }

        if ($oldFee === null && $newFee !== null) {
            return 'price_added';
        }

        if ($oldFee !== null && $newFee === null) {
            return 'price_removed';
        }

        if ($oldReason !== $newReason) {
            return 'changed_reason';
        }

        return 'changed_price';
    }

    private function recordBillingRateChange(
        Schedule $schedule,
        Client $client,
        ?BillingRate $oldRate,
        ?BillingRate $newRate,
        $oldFee,
        $newFee,
        string $action,
        ?string $reason
    ): void {
        ContractorBillingRateChange::create([
            'contractor_id' => $schedule->contractor_id,
            'schedule_id' => $schedule->id,
            'client_id' => $schedule->client_id,
            'billing_rate_id' => $newRate?->id,
            'old_billing_rate_id' => $oldRate?->id,
            'new_billing_rate_id' => $newRate?->id,
            'old_fee' => $this->normalizeFee($oldFee),
            'new_fee' => $this->normalizeFee($newFee),
            'old_rate_label' => $oldRate?->label,
            'new_rate_label' => $newRate?->label,
            'action' => $action,
            'reason' => $reason,
        ]);
    }
}
