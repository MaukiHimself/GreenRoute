<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;

class UpdateClientLocations extends Command
{
    protected $signature = 'clients:update-locations';
    protected $description = 'Parse address field and populate region, district, ward, street columns for existing clients';

    public function handle()
    {
        $this->info('Starting to update client locations...');
        
        $clients = Client::whereNotNull('address')
            ->where('address', '!=', '')
            ->get();
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($clients as $client) {
            // Skip if already has region data
            if ($client->region) {
                $skipped++;
                continue;
            }
            
            // Parse the address field (format: "REGION → DISTRICT → WARD → STREET")
            $address = $client->address;
            
            // Split by arrow separator
            $parts = array_map('trim', explode('→', $address));
            
            if (count($parts) >= 1) {
                $client->region = $parts[0] ?? null;
                $client->district = $parts[1] ?? null;
                $client->ward = $parts[2] ?? null;
                $client->street = $parts[3] ?? null;
                
                $client->save();
                
                $this->line("✓ Updated: {$client->name} - {$address}");
                $updated++;
            }
        }
        
        $this->info("\nCompleted!");
        $this->info("Updated: {$updated} clients");
        $this->info("Skipped: {$skipped} clients (already had region data)");
        
        return 0;
    }
}
