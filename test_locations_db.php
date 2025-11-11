<?php

// Quick test script to check locations database
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Location;

echo "Testing Location Model...\n\n";

try {
    // Test 1: Check if table exists and has records
    $count = Location::count();
    echo "✅ Total locations in database: " . number_format($count) . "\n\n";
    
    if ($count == 0) {
        echo "❌ ERROR: No locations found in database!\n";
        exit(1);
    }
    
    // Test 2: Try to find locations starting with 'ARUSHA'
    $arushaLocations = Location::where('region', 'LIKE', 'ARUSHA%')
        ->orWhere('district', 'LIKE', 'ARUSHA%')
        ->orWhere('ward', 'LIKE', 'ARUSHA%')
        ->limit(5)
        ->get();
    
    echo "✅ Found " . $arushaLocations->count() . " locations matching 'ARUSHA':\n";
    foreach ($arushaLocations as $location) {
        $formatted = implode(' → ', array_filter([
            $location->region,
            $location->district,
            $location->ward,
            $location->street
        ]));
        echo "   - " . $formatted . "\n";
    }
    echo "\n";
    
    // Test 3: Test the autocomplete query
    echo "Testing autocomplete query...\n";
    $query = 'ARUSHA';
    $results = Location::where(function($q) use ($query) {
        $q->where('region', 'LIKE', "{$query}%")
          ->orWhere('district', 'LIKE', "{$query}%")
          ->orWhere('ward', 'LIKE', "{$query}%")
          ->orWhere('street', 'LIKE', "{$query}%");
    })
    ->limit(15)
    ->get()
    ->map(function($location) {
        return [
            'value' => implode(' → ', array_filter([
                $location->region,
                $location->district,
                $location->ward,
                $location->street
            ])),
            'region' => $location->region,
            'district' => $location->district,
            'ward' => $location->ward,
            'street' => $location->street,
        ];
    });
    
    echo "✅ Autocomplete returned " . $results->count() . " results\n";
    echo "\nSample results:\n";
    echo json_encode($results->take(3), JSON_PRETTY_PRINT) . "\n\n";
    
    echo "✅ All tests passed! Location model is working correctly.\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
