<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Schedule;
use App\Models\Invoice;
use Carbon\Carbon;

class ClientDashboardSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test client user
        $clientUser = User::firstOrCreate([
            'email' => 'client@test.com'
        ], [
            'name' => 'John Doe',
            'password' => bcrypt('password'),
            'user_type' => 'client'
        ]);

        // Create a test contractor user
        $contractorUser = User::firstOrCreate([
            'email' => 'contractor@test.com'
        ], [
            'name' => 'Waste Solutions Inc',
            'password' => bcrypt('password'),
            'user_type' => 'contractor'
        ]);

        // Create client record
        $client = Client::firstOrCreate([
            'user_id' => $clientUser->id
        ], [
            'contractor_id' => $contractorUser->id,
            'name' => $clientUser->name,
            'email' => $clientUser->email,
            'phone' => '+255 123 456 789',
            'address' => '123 Main Street, Moshi',
            'city' => 'Moshi',
            'state' => 'Kilimanjaro',
            'zip_code' => '25100',
            'status' => 'active'
        ]);

        // Create sample schedules
        Schedule::firstOrCreate([
            'contractor_id' => $contractorUser->id,
            'client_id' => $client->id,
            'pickup_date' => Carbon::now()->addDays(3)
        ], [
            'pickup_time' => '09:00:00',
            'pickup_location' => 'Front of house',
            'pickup_address' => $client->address,
            'city' => $client->city,
            'state' => $client->state,
            'zip_code' => $client->zip_code,
            'service_type' => 'collection',
            'status' => 'scheduled',
            'notes' => 'Regular waste collection'
        ]);

        Schedule::firstOrCreate([
            'contractor_id' => $contractorUser->id,
            'client_id' => $client->id,
            'pickup_date' => Carbon::now()->addDays(7)
        ], [
            'pickup_time' => '10:00:00',
            'pickup_location' => 'Back yard',
            'pickup_address' => $client->address,
            'city' => $client->city,
            'state' => $client->state,
            'zip_code' => $client->zip_code,
            'service_type' => 'disposal',
            'status' => 'scheduled',
            'notes' => 'Recycling pickup'
        ]);

        // Create sample invoices
        Invoice::firstOrCreate([
            'invoice_number' => 'INV-2025-0001'
        ], [
            'contractor_id' => $contractorUser->id,
            'client_id' => $client->id,
            'invoice_date' => Carbon::now()->subDays(15),
            'due_date' => Carbon::now()->subDays(5),
            'status' => 'paid',
            'subtotal' => 150.00,
            'tax_rate' => 18.00,
            'tax_amount' => 27.00,
            'total_amount' => 177.00,
            'service_type' => 'Waste Collection',
            'description' => 'Monthly waste collection service',
            'amount_paid' => 177.00,
            'paid_at' => Carbon::now()->subDays(10)
        ]);

        Invoice::firstOrCreate([
            'invoice_number' => 'INV-2025-0002'
        ], [
            'contractor_id' => $contractorUser->id,
            'client_id' => $client->id,
            'invoice_date' => Carbon::now()->subDays(5),
            'due_date' => Carbon::now()->addDays(25),
            'status' => 'sent',
            'subtotal' => 150.00,
            'tax_rate' => 18.00,
            'tax_amount' => 27.00,
            'total_amount' => 177.00,
            'service_type' => 'Waste Collection',
            'description' => 'Monthly waste collection service',
            'amount_paid' => 0.00
        ]);
    }
}