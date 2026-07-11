<?php

/**
 * One-off presentation seed: contractor "DENIS MAUKI" + 5 routes + 20 clients
 * across distinct Dar es Salaam locations. Idempotent — safe to re-run.
 *
 * Run:  php artisan tinker < database/seed_denis_presentation.php
 */

use App\Models\User;
use App\Models\Client;
use App\Models\Contractor;
use App\Models\ContractorRoute;
use Illuminate\Support\Facades\DB;

$PASSWORD       = 'Mauki@2003';
$CONTRACTOR_EMAIL = 'denismauki@greenroute.co.tz';
$DUMP_SITE      = 'Pugu Kinyamwezi Dumpsite';
$BASE_LAT       = -6.7667;   // Mikocheni yard
$BASE_LNG       = 39.2500;

// --- Route definitions --------------------------------------------------
$routes = [
    ['name' => 'Masaki–Oysterbay Loop',    'district' => 'Kinondoni', 'color' => '#047857'],
    ['name' => 'Sinza–Mwananyamala Route',  'district' => 'Kinondoni', 'color' => '#2563eb'],
    ['name' => 'Kariakoo–Upanga Route',     'district' => 'Ilala',     'color' => '#c0392b'],
    ['name' => 'Temeke–Mbagala Route',      'district' => 'Temeke',    'color' => '#d97706'],
    ['name' => 'Ubungo–Tabata Route',       'district' => 'Ubungo',    'color' => '#7c3aed'],
];

// --- 20 clients (name, place/ward, district, lat, lng, email, route) -----
$clients = [
    // Route 1 — Masaki–Oysterbay Loop (Kinondoni)
    ['Juma Hassan',    'Masaki',      'Kinondoni', -6.7420, 39.2790, 'juma.masaki@greenroute.co.tz',        0],
    ['Neema Joseph',   'Oysterbay',   'Kinondoni', -6.7700, 39.2830, 'neema.oysterbay@greenroute.co.tz',    0],
    ['Salma Rashid',   'Msasani',     'Kinondoni', -6.7550, 39.2720, 'salma.msasani@greenroute.co.tz',      0],
    ['Baraka Mushi',   'Mikocheni',   'Kinondoni', -6.7667, 39.2500, 'baraka.mikocheni@greenroute.co.tz',   0],
    // Route 2 — Sinza–Mwananyamala Route (Kinondoni)
    ['Amina Said',     'Mwananyamala','Kinondoni', -6.7850, 39.2450, 'amina.mwananyamala@greenroute.co.tz', 1],
    ['Frank Peter',    'Kijitonyama', 'Kinondoni', -6.7720, 39.2380, 'frank.kijitonyama@greenroute.co.tz',  1],
    ['Zainabu Ally',   'Sinza',       'Kinondoni', -6.7830, 39.2200, 'zainabu.sinza@greenroute.co.tz',      1],
    ['Emanuel John',   'Kinondoni',   'Kinondoni', -6.7900, 39.2600, 'emanuel.kinondoni@greenroute.co.tz',  1],
    // Route 3 — Kariakoo–Upanga Route (Ilala)
    ['Rehema Omary',   'Kariakoo',    'Ilala',     -6.8180, 39.2760, 'rehema.kariakoo@greenroute.co.tz',    2],
    ['Daniel Mbwana',  'Upanga',      'Ilala',     -6.8080, 39.2880, 'daniel.upanga@greenroute.co.tz',      2],
    ['Fatuma Juma',    'Ilala',       'Ilala',     -6.8200, 39.2830, 'fatuma.ilala@greenroute.co.tz',       2],
    ['Peter Mgeni',    'Buguruni',    'Ilala',     -6.8300, 39.2500, 'peter.buguruni@greenroute.co.tz',     2],
    // Route 4 — Temeke–Mbagala Route (Temeke)
    ['Halima Kassim',  'Temeke',      'Temeke',    -6.8600, 39.2800, 'halima.temeke@greenroute.co.tz',      3],
    ['Joseph Massawe', 'Mbagala',     'Temeke',    -6.9200, 39.2600, 'joseph.mbagala@greenroute.co.tz',     3],
    ['Grace Elias',    "Chang'ombe",  'Temeke',    -6.8400, 39.2700, 'grace.changombe@greenroute.co.tz',    3],
    ['Yusuf Iddi',     'Kurasini',    'Temeke',    -6.8500, 39.2900, 'yusuf.kurasini@greenroute.co.tz',     3],
    // Route 5 — Ubungo–Tabata Route (Ubungo)
    ['Anna Michael',   'Ubungo',      'Ubungo',    -6.8000, 39.2100, 'anna.ubungo@greenroute.co.tz',        4],
    ['Said Bakari',    'Kimara',      'Ubungo',    -6.7700, 39.1600, 'said.kimara@greenroute.co.tz',        4],
    ['Mariam Hamisi',  'Tabata',      'Ubungo',    -6.8400, 39.2200, 'mariam.tabata@greenroute.co.tz',      4],
    ['David Kileo',    'Mbezi',       'Ubungo',    -6.7400, 39.1600, 'david.mbezi@greenroute.co.tz',        4],
];

DB::transaction(function () use ($routes, $clients, $PASSWORD, $CONTRACTOR_EMAIL, $DUMP_SITE, $BASE_LAT, $BASE_LNG) {

    // ---- Clean any previous run for this contractor -------------------
    $existing = User::where('email', $CONTRACTOR_EMAIL)->first();
    if ($existing) {
        $clientRows = Client::where('contractor_id', $existing->id)->get();
        $userIds = $clientRows->pluck('user_id')->filter()->all();
        Client::where('contractor_id', $existing->id)->delete();
        User::whereIn('id', $userIds)->delete();
        ContractorRoute::where('contractor_id', $existing->id)->delete();
        Contractor::where('user_id', $existing->id)->delete();
        $existing->delete();
    }

    // ---- Contractor user + profile ------------------------------------
    $contractor = User::create([
        'name'                   => 'DENIS MAUKI',
        'username'               => 'denismauki',
        'email'                  => $CONTRACTOR_EMAIL,
        'password'               => $PASSWORD,          // hashed by the model cast
        'user_type'              => 'contractor',
        'status'                 => 'approved',
        'subscription_completed' => true,
        'subscription_status'    => 'active',
        'subscription_date'      => now(),
        'latitude'               => $BASE_LAT,
        'longitude'              => $BASE_LNG,
        'location_address'       => 'Mikocheni, Kinondoni, Dar es Salaam',
    ]);

    Contractor::create([
        'user_id'             => $contractor->id,
        'company_name'        => 'DENIS MAUKI Waste Services',
        'name'                => 'DENIS MAUKI',
        'email'               => $CONTRACTOR_EMAIL,
        'phone'               => '+255713000001',
        'address'             => 'Mikocheni, Kinondoni, Dar es Salaam',
        'region'              => 'Dar es Salaam',
        'district'            => 'Kinondoni',
        'ward'                => 'Mikocheni',
        'license_number'      => 'DM-LIC-2003',
        'vehicle_type'        => 'medium',
        'license_plate'       => 'T123 DEN',
        'registration_number' => 'CTR' . str_pad((string) $contractor->id, 6, '0', STR_PAD_LEFT),
    ]);

    // ---- Routes -------------------------------------------------------
    $routeModels = [];
    foreach ($routes as $r) {
        $routeModels[] = ContractorRoute::create([
            'contractor_id' => $contractor->id,
            'route_name'    => $r['name'],
            'region'        => 'Dar es Salaam',
            'district'      => $r['district'],
            'ward'          => null,
            'street'        => null,
            'dumping_site'  => $DUMP_SITE,
            'description'   => $r['name'] . ' — weekly residential collection.',
            'color'         => $r['color'],
            'is_active'     => true,
        ]);
    }

    // ---- Clients (+ portal user each) ---------------------------------
    $i = 0;
    foreach ($clients as $c) {
        [$name, $place, $district, $lat, $lng, $email, $routeIdx] = $c;
        $i++;

        $clientUser = User::create([
            'name'                   => $name,
            'username'               => $email,
            'email'                  => $email,
            'password'               => $PASSWORD,       // hashed by the model cast
            'user_type'              => 'client',
            'status'                 => 'active',
            'subscription_completed' => true,
            'subscription_status'    => 'active',
            'latitude'               => $lat,
            'longitude'              => $lng,
            'location_address'       => $place . ', ' . $district . ', Dar es Salaam',
        ]);

        Client::create([
            'contractor_id'  => $contractor->id,
            'user_id'        => $clientUser->id,
            'name'           => $name,
            'contact_name'   => $name,
            'category'       => 'Residential',
            'email'          => $email,
            'phone'          => '+2557130' . str_pad((string) (10 + $i), 5, '0', STR_PAD_LEFT),
            'phone_2'        => '+2556780' . str_pad((string) (10 + $i), 5, '0', STR_PAD_LEFT),
            'address'        => $place . ', ' . $district . ', Dar es Salaam',
            'region'         => 'Dar es Salaam',
            'district'       => $district,
            'ward'           => $place,
            'street'         => $place,
            'latitude'       => $lat,
            'longitude'      => $lng,
            'city'           => 'Dar es Salaam',
            'state'          => 'Dar es Salaam',
            'zip_code'       => 'N/A',
            'status'         => 'active',
            'self_registered'=> false,
            'verified_at'    => now(),
            'route'          => $routeModels[$routeIdx]->route_name,
            'route_sequence' => $i,
        ]);
    }

    echo "Created contractor #{$contractor->id} with " . count($routes) . " routes and " . count($clients) . " clients.\n";
});

echo "DONE\n";
