<?php

/**
 * Presentation seed #3 — truck collection-run history for DENIS MAUKI.
 *
 * Fills the "Field Operations & Waste Collected" report section and the
 * truck's Collection History drawer with ~10 weeks of completed runs:
 *
 *  - Truck T123 DEN gets a tare weight (needed by the weighbridge form).
 *  - 25 completed collection runs (5 collection days × 5 routes), each with
 *    per-client stop outcomes (collected / skipped / blocked) and a
 *    weighbridge reading (gross − tare = net kg) with prorated per-client
 *    shares — this powers success rate, waste-by-route and top-clients.
 *
 * Idempotent — wipes and rebuilds only this contractor's collection runs.
 *
 * Run:  php artisan tinker --execute="require base_path('database/seed_denis_truck_history.php');"
 */

use App\Models\User;
use App\Models\Client;
use App\Models\Truck;
use App\Models\ContractorRoute;
use App\Models\CollectionRun;
use App\Models\CollectionRunStop;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$CONTRACTOR_EMAIL = 'denismauki@greenroute.co.tz';

$user = User::where('email', $CONTRACTOR_EMAIL)->first();
if (!$user) {
    echo "Contractor not found — run seed_denis_presentation.php first.\n";
    return;
}

$truck = Truck::where('contractor_id', $user->id)->first();
if (!$truck) {
    echo "No truck found for contractor — register truck T123 DEN first.\n";
    return;
}

DB::transaction(function () use ($user, $truck) {

    // ---- Clean previous runs for this contractor ------------------------
    $runIds = CollectionRun::where('contractor_id', $user->id)->pluck('id');
    CollectionRunStop::whereIn('collection_run_id', $runIds)->delete();
    CollectionRun::where('contractor_id', $user->id)->delete();

    // ---- Truck tare weight (weighbridge needs it) ------------------------
    $TARE = 4100.0; // kg — medium truck, empty
    $truck->update(['tare_weight_kg' => $TARE]);

    // ---- Routes with their clients ---------------------------------------
    $routes = ContractorRoute::where('contractor_id', $user->id)->orderBy('id')->get();
    $clientsByRoute = Client::where('contractor_id', $user->id)
        ->orderBy('route_sequence')
        ->get()
        ->groupBy('route');

    // Same collection days as the completed schedules in seed #2.
    $collectionDays = [
        Carbon::create(2026, 5, 4),  Carbon::create(2026, 5, 18),
        Carbon::create(2026, 6, 1),  Carbon::create(2026, 6, 15),
        Carbon::create(2026, 7, 6),
    ];

    $runCount = 0;
    $stopCount = 0;
    $totalNet = 0.0;

    foreach ($collectionDays as $d => $day) {
        foreach ($routes as $r => $route) {
            $clients = $clientsByRoute[$route->route_name] ?? collect();
            if ($clients->isEmpty()) {
                continue;
            }

            // Truck does one route after another through the day.
            $start = $day->copy()->setTime(7 + $r * 2, ($r * 17) % 60);
            $end   = $start->copy()->addMinutes(75 + (($d + $r) * 13) % 40);

            // Deterministic outcome mix: mostly collected, occasional
            // skip (client not ready) or block (road inaccessible).
            $statuses = [];
            foreach ($clients as $i => $client) {
                $roll = ($d * 7 + $r * 5 + $i * 3) % 12;
                $statuses[$client->id] = $roll === 5 ? 'skipped' : ($roll === 11 ? 'blocked' : 'collected');
            }
            $collected = count(array_keys($statuses, 'collected'));
            $skipped   = count(array_keys($statuses, 'skipped'));
            $blocked   = count(array_keys($statuses, 'blocked'));

            // Weighbridge reading after the route: ~28–45 kg per collected stop.
            $net   = $collected * (28 + (($d * 11 + $r * 7) % 18)) + (($d + $r) % 10);
            $gross = 4100.0 + $net;

            $run = CollectionRun::create([
                'truck_id' => $truck->id,
                'contractor_id' => $user->id,
                'route_id' => $route->id,
                'route_name' => $route->route_name,
                'started_at' => $start,
                'completed_at' => $end,
                'total_stops' => $clients->count(),
                'collected_count' => $collected,
                'skipped_count' => $skipped,
                'blocked_count' => $blocked,
                'gross_weight_kg' => $gross,
                'tare_weight_kg' => 4100.0,
                'net_weight_kg' => $net,
                'weighed_at' => $end->copy()->addMinutes(45),
                'status' => 'completed',
            ]);
            $run->created_at = $start;
            $run->updated_at = $end->copy()->addMinutes(45);
            $run->save();

            // Per-stop records; net weight prorated across collected stops.
            $share = $collected > 0 ? round($net / $collected, 1) : null;
            $minute = 0;
            foreach ($clients as $client) {
                $status = $statuses[$client->id];
                $stop = CollectionRunStop::create([
                    'collection_run_id' => $run->id,
                    'client_id' => $client->id,
                    'client_name' => $client->name,
                    'status' => $status,
                    'prorated_weight_kg' => $status === 'collected' ? $share : null,
                    'actioned_at' => $start->copy()->addMinutes(10 + $minute),
                ]);
                $stop->created_at = $stop->actioned_at;
                $stop->updated_at = $stop->actioned_at;
                $stop->save();
                $minute += 18;
                $stopCount++;
            }

            $runCount++;
            $totalNet += $net;
        }
    }

    echo "Truck: {$truck->plate_number} (tare 4,100 kg)\n";
    echo "Runs:  {$runCount} completed, {$stopCount} stops\n";
    echo "Waste weighed: " . number_format($totalNet, 1) . " kg\n";
});

echo "DONE\n";
