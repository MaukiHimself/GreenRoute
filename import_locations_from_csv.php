<?php

/**
 * Import locations from CSV file to database
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  Importing Locations from CSV                             ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

$csvFile = __DIR__.'/storage/app/tbl_locations.csv';

if (!file_exists($csvFile)) {
    echo "❌ CSV file not found at: $csvFile\n";
    exit(1);
}

echo "📄 CSV file: $csvFile\n";

try {
    // Truncate existing data
    DB::table('tbl_locations')->truncate();
    echo "🗑️  Cleared existing locations\n\n";

    // Open and read CSV
    $handle = fopen($csvFile, 'r');
    if (!$handle) {
        throw new Exception("Cannot open CSV file");
    }

    $rowCount = 0;
    $importedCount = 0;
    $batchSize = 1000;
    $batch = [];

    // Skip header row
    fgetcsv($handle);

    echo "📥 Reading and importing CSV data...\n";

    while (($row = fgetcsv($handle)) !== false) {
        $rowCount++;

        // Extract columns: district_id, region, regioncode, district, districtcode, ward, wardcode, street, places, POSTCODE, POSTCODED, POSTCODEW
        if (count($row) < 8) {
            continue;
        }

        $location = [
            'region' => trim($row[1] ?? ''),
            'district' => trim($row[3] ?? ''),
            'ward' => trim($row[5] ?? ''),
            'street' => trim($row[7] ?? ''),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Skip rows with missing required fields
        if (empty($location['region']) || empty($location['district']) || empty($location['ward'])) {
            continue;
        }

        $batch[] = $location;

        // Insert batch when it reaches the size
        if (count($batch) >= $batchSize) {
            DB::table('tbl_locations')->insert($batch);
            $importedCount += count($batch);
            echo "✓ Imported $importedCount locations...\n";
            $batch = [];
        }
    }

    // Insert remaining batch
    if (!empty($batch)) {
        DB::table('tbl_locations')->insert($batch);
        $importedCount += count($batch);
    }

    fclose($handle);

    echo "\n✅ Import completed!\n";
    echo "   Total rows processed: $rowCount\n";
    echo "   Total locations imported: $importedCount\n\n";

    // Verify import
    $totalLocations = DB::table('tbl_locations')->count();
    $regions = DB::table('tbl_locations')->select('region')->distinct()->count();
    
    echo "📊 Database Statistics:\n";
    echo "   Total locations: $totalLocations\n";
    echo "   Unique regions: $regions\n";

} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
