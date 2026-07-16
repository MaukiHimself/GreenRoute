<?php

namespace App\Http\Controllers;

use App\Models\BillingRate;
use App\Models\ContractorBillingRateChange;
use App\Models\Schedule;
use App\Models\Client;
use App\Models\Location;
use App\Models\ContractorRoute;
use App\Notifications\GenericNotification;
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

        // Client service requests waiting to be assigned to a route schedule.
        $pendingRequests = Schedule::forContractor(Auth::id())
            ->where('status', 'requested')
            ->with(['client'])
            ->orderBy('created_at')
            ->get();

        $routes = ContractorRoute::where('contractor_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('route_name')
            ->pluck('route_name');

        return view('schedules.index', compact('schedules', 'pendingRequests', 'routes'));
    }

    /**
     * Assign a client service request to a route and confirm its pickup
     * date, turning it into a normal scheduled pickup. Notifies the client.
     */
    public function assignRequest(Request $request, Schedule $schedule)
    {
        if ($schedule->contractor_id !== Auth::id() || $schedule->status !== 'requested') {
            abort(404);
        }

        $validated = $request->validate([
            'route_name' => 'required|string|max:255',
            'pickup_date' => 'required|date|after_or_equal:today',
        ]);

        $schedule->update([
            'route' => $validated['route_name'],
            'pickup_date' => $validated['pickup_date'],
            'scheduled_date' => $validated['pickup_date'],
            'status' => 'scheduled',
        ]);

        // Keep the client's route assignment in sync so future bulk
        // schedules for this route include them.
        $client = $schedule->client;
        if ($client && !$client->route) {
            $client->update(['route' => $validated['route_name']]);
        }

        if ($client && $client->user) {
            $client->user->notify(new GenericNotification(
                title: 'Pickup confirmed',
                message: 'Your ' . $schedule->service_type . ' request has been scheduled for '
                    . \Carbon\Carbon::parse($validated['pickup_date'])->format('M d, Y')
                    . ' on route ' . $validated['route_name'] . '.',
                url: route('client.schedules'),
                icon: 'bi-calendar-check',
            ));
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Request assigned to route "' . $validated['route_name'] . '" and the client has been notified.');
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
                ->select('id', 'route_name', 'region', 'district', 'ward', 'street')
                ->orderBy('route_name')
                ->get();

            // Client counts per route so the picker shows real coverage.
            $routeClientCounts = Client::where('contractor_id', Auth::id())
                ->whereNotNull('route')
                ->select('route', DB::raw('count(*) as total'))
                ->groupBy('route')
                ->pluck('total', 'route');

            // The contractor's own published price list — schedules are priced
            // from it so clients see the same price everywhere.
            $servicePrices = \App\Models\ServicePrice::where('contractor_id', Auth::id())
                ->where('is_active', true)
                ->orderBy('service_type')
                ->orderBy('price')
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

            return view('contractor.create-schedule', compact('contractor', 'clients', 'assignedClient', 'regions', 'routes', 'routeClientCounts', 'siteLocations', 'billingRates', 'billingRatesData', 'servicePrices'));
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
            'route_name' => 'nullable|string|max:255',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'pickup_location' => 'nullable|string',
            'service_type' => 'required|string',
            'frequency' => 'nullable|in:once,weekly,twice_month,thrice_month,monthly',
            'repeat_until' => 'nullable|date|after_or_equal:pickup_date',
            'estimated_duration' => 'nullable|numeric',
            'total_volume' => 'nullable|numeric',
            'disposal_site' => 'nullable|string',
            'notes' => 'nullable|string',
            'site_location' => 'nullable|string',
            'billing_rate_id' => 'nullable|exists:billing_rates,id',
            'contractor_adjusted_fee' => 'nullable|numeric|min:0',
            'billing_rate_change_reason' => 'nullable|string|max:1000',
        ]);

        $frequency = $validated['frequency'] ?? 'once';
        if ($frequency !== 'once' && empty($validated['repeat_until'])) {
            return back()->withInput()->withErrors([
                'repeat_until' => 'Please choose the date the repeating schedule should run until.',
            ]);
        }

        // Expand the recurrence into concrete pickup dates (capped at 31).
        $pickupDates = [\Carbon\Carbon::parse($validated['pickup_date'])];
        if ($frequency !== 'once') {
            $until = \Carbon\Carbon::parse($validated['repeat_until'])->endOfDay();
            $cursor = $pickupDates[0]->copy();
            while (count($pickupDates) < 31) {
                $cursor = match ($frequency) {
                    'weekly' => $cursor->copy()->addDays(7),
                    'twice_month' => $cursor->copy()->addDays(14),
                    'thrice_month' => $cursor->copy()->addDays(10),
                    'monthly' => $cursor->copy()->addMonthNoOverflow(),
                };
                if ($cursor->gt($until)) {
                    break;
                }
                $pickupDates[] = $cursor;
            }
        }
        $frequencyLabels = [
            'once' => null,
            'weekly' => 'weekly',
            'twice_month' => 'twice per month',
            'thrice_month' => 'thrice per month',
            'monthly' => 'monthly',
        ];

        $contractor = Auth::user();

        // Block scheduling for any client that has an overdue, unpaid invoice.
        $blocked = Client::where('contractor_id', $contractor->id)
            ->whereIn('id', $validated['client_ids'])
            ->get()
            ->filter->hasOverdueUnpaidInvoice()
            ->pluck('name');

        if ($blocked->isNotEmpty()) {
            return back()->withInput()->withErrors([
                'client_ids' => 'Cannot schedule for ' . $blocked->implode(', ') .
                    ' — they have an overdue unpaid invoice. Please settle it before scheduling a new pickup.',
            ]);
        }

        $billingRate = $this->resolveBillingRate($validated['billing_rate_id'] ?? null);
        $contractorAdjustedFee = $this->normalizeFee($request->contractor_adjusted_fee);
        $schedulePrice = $contractorAdjustedFee ?? optional($billingRate)->collection_fee;
        $reason = $validated['billing_rate_change_reason'] ?? null;

        $created = 0;
        DB::transaction(function () use ($validated, $contractor, $billingRate, $contractorAdjustedFee, $schedulePrice, $reason, $pickupDates, $frequency, $frequencyLabels, &$created) {
            foreach ($validated['client_ids'] as $clientId) {
                $client = Client::where('id', $clientId)
                    ->where('contractor_id', $contractor->id)
                    ->firstOrFail();

                $firstSchedule = null;
                foreach ($pickupDates as $pickupDate) {
                    $schedule = Schedule::create([
                        'contractor_id' => $contractor->id,
                        'client_id' => $client->id,
                        'contractor_registration_number' => $contractor->registration_number,
                        'client_registration_number' => $client->registration_number,
                        'route' => $validated['route_name'] ?? $client->route ?? 'Not Assigned',
                        'billing_rate_id' => $billingRate?->id,
                        'billing_rate_category' => $billingRate?->category,
                        'billing_rate_location' => $billingRate?->location,
                        'billing_rate_frequency' => $billingRate?->frequency,
                        'base_collection_fee' => $billingRate?->collection_fee,
                        'contractor_adjusted_fee' => $contractorAdjustedFee,
                        'schedule_price' => $schedulePrice,
                        'billing_rate_change_reason' => $reason,
                        'billing_rate_modified_at' => $billingRate || $contractorAdjustedFee !== null ? now() : null,
                        'pickup_date' => $pickupDate->toDateString(),
                        'pickup_time' => $validated['pickup_time'],
                        'scheduled_date' => $pickupDate->toDateString(),
                        'scheduled_time' => $validated['pickup_time'],
                        'pickup_location' => ($validated['pickup_location'] ?? null)
                            ?: ($client->ward ?: ($validated['route_name'] ?? $client->route ?? 'Client premises')),
                        'pickup_address' => $client->address ?? 'Not Provided',
                        'city' => $client->city ?? 'Not Provided',
                        'state' => $client->state ?? 'Not Provided',
                        'zip_code' => $client->zip_code ?? '00000',
                        'service_type' => $validated['service_type'],
                        'frequency' => $frequencyLabels[$frequency],
                        'status' => 'scheduled',
                        'estimated_duration' => $validated['estimated_duration'] ?? null,
                        'total_volume' => $validated['total_volume'] ?? null,
                        'disposal_site' => $validated['disposal_site'] ?? null,
                        'notes' => $validated['notes'] ?? null,
                    ]);
                    $firstSchedule ??= $schedule;
                    $created++;

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

                // One bell notification per client, covering the whole series.
                if ($client->user && $firstSchedule) {
                    $message = count($pickupDates) === 1
                        ? 'A ' . $validated['service_type'] . ' pickup has been scheduled for ' . $pickupDates[0]->format('M d, Y')
                        : ucfirst($frequencyLabels[$frequency]) . ' ' . $validated['service_type'] . ' pickups scheduled: ' . count($pickupDates) . ' dates starting ' . $pickupDates[0]->format('M d, Y');
                    $client->user->notify(new GenericNotification(
                        title: 'Pickup scheduled',
                        message: $message,
                        url: route('client.schedules'),
                        icon: 'bi-calendar-check',
                    ));
                }
            }
        });

        return redirect()->route('schedules.index')
            ->with('success', $created . ' schedule' . ($created === 1 ? '' : 's') . ' created successfully.');
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

        $servicePrices = \App\Models\ServicePrice::where('contractor_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('service_type')
            ->orderBy('price')
            ->get();

        return view('schedules.edit', compact('schedule', 'clients', 'billingRates', 'servicePrices'));
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
            'pickup_location' => 'nullable|string',
            'pickup_address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
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

        // Block (re)scheduling a future pickup for a client with an overdue unpaid
        // invoice. Editing to complete/cancel a pickup stays allowed.
        if ($validated['status'] === 'scheduled'
            && \Carbon\Carbon::parse($validated['pickup_date'])->startOfDay()->gte(now()->startOfDay())
            && $client->hasOverdueUnpaidInvoice()) {
            return back()->withInput()->withErrors([
                'client_id' => 'Cannot schedule for ' . $client->name .
                    ' — they have an overdue unpaid invoice. Please settle it before scheduling a new pickup.',
            ]);
        }

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
            'pickup_location' => ($validated['pickup_location'] ?? null) ?: ($client->ward ?: $schedule->pickup_location),
            'pickup_address' => ($validated['pickup_address'] ?? null) ?: ($client->address ?? $schedule->pickup_address),
            'city' => ($validated['city'] ?? null) ?: ($client->city ?? $schedule->city),
            'state' => ($validated['state'] ?? null) ?: ($client->state ?? $schedule->state),
            'zip_code' => ($validated['zip_code'] ?? null) ?: ($client->zip_code ?? $schedule->zip_code),
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

        // Notify the client (bell) that their schedule was updated
        if ($client->user) {
            $client->user->notify(new GenericNotification(
                title: 'Schedule updated',
                message: 'Your ' . $validated['service_type'] . ' pickup on ' . \Carbon\Carbon::parse($validated['pickup_date'])->format('M d, Y') . ' has been updated',
                url: route('client.schedules'),
                icon: 'bi-calendar2-event',
            ));
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

        // Notify the client (bell) on meaningful status changes
        $notifyStatuses = [
            'in_progress' => ['title' => 'Pickup on the way', 'message' => 'Your waste collection truck is en route to your location.', 'icon' => 'bi-truck'],
            'completed'   => ['title' => 'Pickup completed', 'message' => 'Your waste collection has been completed successfully.', 'icon' => 'bi-check-circle'],
            'cancelled'   => ['title' => 'Pickup cancelled', 'message' => 'Your scheduled pickup has been cancelled. Please contact your contractor for details.', 'icon' => 'bi-x-circle'],
        ];

        if (isset($notifyStatuses[$validated['status']]) && $validated['status'] !== $oldStatus) {
            $clientRecord = $schedule->client;
            if ($clientRecord && $clientRecord->user) {
                $n = $notifyStatuses[$validated['status']];
                $clientRecord->user->notify(new GenericNotification(
                    title: $n['title'],
                    message: $n['message'],
                    url: route('client.schedules'),
                    icon: $n['icon'],
                ));
            }
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
