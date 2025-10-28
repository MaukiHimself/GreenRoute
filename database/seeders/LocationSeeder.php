<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if locations already exist
        if (DB::table('tbl_locations')->count() > 0) {
            $this->command->info('Locations already seeded. Skipping...');
            return;
        }

        $this->command->info('Importing location data from SQL file...');

        try {
            // Read the SQL file
            $sqlPath = storage_path('app/tbl_locations.sql');
            
            if (!file_exists($sqlPath)) {
                $this->command->error('SQL file not found at: ' . $sqlPath);
                return;
            }

            $sql = file_get_contents($sqlPath);
            
            // Clean up the SQL (remove comments and fix formatting)
            $sql = preg_replace('/--.*$/m', '', $sql); // Remove single-line comments
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remove multi-line comments
            $sql = preg_replace('/^\s*$/m', '', $sql); // Remove empty lines
            
            // Split by semicolons to get individual statements
            $statements = array_filter(
                array_map('trim', explode(';', $sql)),
                function($stmt) {
                    return !empty($stmt) && stripos($stmt, 'INSERT INTO') !== false;
                }
            );

            if (empty($statements)) {
                $this->command->error('No valid INSERT statements found in SQL file.');
                return;
            }

            // Execute each INSERT statement
            DB::beginTransaction();
            
            $imported = 0;
            foreach ($statements as $statement) {
                try {
                    DB::unprepared($statement . ';');
                    $imported++;
                } catch (\Exception $e) {
                    $this->command->warn('Failed to execute statement: ' . substr($statement, 0, 100) . '...');
                    $this->command->warn('Error: ' . $e->getMessage());
                }
            }

            DB::commit();
            
            $this->command->info("Successfully imported {$imported} location records.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error importing locations: ' . $e->getMessage());
        }
    }
}
