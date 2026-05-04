<?php

/**
 * Create two demo users: one client and one contractor.
 *
 * Usage:
 *   php create-demo-users.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;
use App\Models\Contractor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

function createOrUpdateUser(array $data): User
{
    return User::updateOrCreate(
        ['email' => $data['email']],
        $data
    );
}

$contractorEmail = 'contractor@example.com';
$contractorPassword = 'Contractor@123';
$clientEmail = 'client@example.com';
$clientPassword = 'Client@123';

echo "\nCreating demo users...\n\n";

$contractorUser = createOrUpdateUser([
    'name' => 'Demo Contractor',
    'email' => $contractorEmail,
    'password' => Hash::make($contractorPassword),
    'user_type' => 'contractor',
    'status' => 'approved',
    'email_verified_at' => now(),
]);

$contractor = Contractor::updateOrCreate(
    ['user_id' => $contractorUser->id],
    [
        'company_name' => 'Demo Contractor Services',
        'name' => 'Demo Contractor',
        'email' => $contractorEmail,
        'phone' => '+255700000001',
        'address' => '123 Demo Street, Dar es Salaam',
        'site_locations' => 'Mikocheni, Dar es Salaam',
        'region' => 'Dar es Salaam',
        'district' => 'Kinondoni',
        'ward' => 'Mikocheni',
        'street' => 'Demo Street',
        'license_number' => 'CTR-0001',
        'certificate_path' => null,
    ]
);

$clientUser = createOrUpdateUser([
    'name' => 'Demo Client',
    'email' => $clientEmail,
    'password' => Hash::make($clientPassword),
    'user_type' => 'client',
    'email_verified_at' => now(),
]);

$client = Client::updateOrCreate(
    ['email' => $clientEmail],
    [
        'contractor_id' => $contractorUser->id,
        'user_id' => $clientUser->id,
        'name' => 'Demo Client',
        'contact_name' => 'Demo Client',
        'email' => $clientEmail,
        'phone' => '+255700000002',
        'address' => '456 Demo Avenue, Dar es Salaam',
        'city' => 'Dar es Salaam',
        'state' => 'Dar es Salaam',
        'zip_code' => '14113',
        'latitude' => -6.7924,
        'longitude' => 39.2083,
        'status' => 'active',
    ]
);

echo "✅ Demo users created or updated successfully!\n\n";
echo "Contractor:\n";
echo "  Email: $contractorEmail\n";
echo "  Password: $contractorPassword\n";
echo "  Login URL: http://localhost:8000/login/contractor\n\n";

echo "Client:\n";
echo "  Email: $clientEmail\n";
echo "  Password: $clientPassword\n";
echo "  Login URL: http://localhost:8000/client/login\n\n";

echo "Note: if you already had users with these emails, their records were updated.\n";
