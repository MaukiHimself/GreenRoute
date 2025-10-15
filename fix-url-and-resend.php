<?php

/**
 * Fix APP_URL and Resend Invitation
 * 
 * This script helps you fix the URL issue and resend invitation
 * 
 * Usage: php fix-url-and-resend.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║          Fix URL and Resend Invitation                  ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Check current APP_URL
$currentUrl = config('app.url');
echo "📋 Current APP_URL Configuration\n";
echo "══════════════════════════════════════════════════════════\n";
echo "Current: " . $currentUrl . "\n\n";

// Check if it has port
if (!preg_match('/:\d+/', $currentUrl) && !preg_match('/https?:\/\/[^\/]+\.[a-z]+/', $currentUrl)) {
    echo "⚠️  WARNING: APP_URL doesn't include a port number!\n";
    echo "   This will cause email links to not work.\n\n";
    
    echo "🔧 Suggested Fix:\n";
    echo "══════════════════════════════════════════════════════════\n";
    echo "1. Open: .env file\n";
    echo "2. Find: APP_URL=" . $currentUrl . "\n";
    echo "3. Change to: APP_URL=http://localhost:8000\n";
    echo "   (Or use your actual port number)\n";
    echo "4. Run: php artisan config:clear\n";
    echo "5. Run this script again\n\n";
    
    echo "Quick command to update (Windows):\n";
    echo "══════════════════════════════════════════════════════════\n";
    echo "1. Edit .env manually, OR\n";
    echo "2. In PowerShell:\n";
    echo "   (Get-Content .env) -replace 'APP_URL=http://localhost', 'APP_URL=http://localhost:8000' | Set-Content .env\n";
    echo "   php artisan config:clear\n\n";
    
    echo "Press Enter after you've updated .env to continue...\n";
    $handle = fopen("php://stdin", "r");
    fgets($handle);
    
    // Clear config cache
    echo "🔄 Clearing configuration cache...\n";
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    echo "✓ Cache cleared!\n\n";
    
    // Reload config
    $currentUrl = config('app.url');
    echo "📋 New APP_URL: " . $currentUrl . "\n\n";
}

// Generate test URLs
echo "🔍 Testing URL Generation\n";
echo "══════════════════════════════════════════════════════════\n";
$clientLoginUrl = url('/client/login');
$dashboardUrl = url('/dashboard');

echo "Client Login URL: " . $clientLoginUrl . "\n";
echo "Dashboard URL: " . $dashboardUrl . "\n\n";

// Check if URLs look correct
if (preg_match('/:\d+/', $clientLoginUrl) || preg_match('/https?:\/\/[^\/]+\.[a-z]+/', $clientLoginUrl)) {
    echo "✅ URLs look correct!\n\n";
} else {
    echo "❌ URLs still don't have port numbers.\n";
    echo "   Make sure you updated .env and ran: php artisan config:clear\n\n";
    exit(1);
}

// Find client
echo "🔍 Finding client: Calvin Junior\n";
echo "══════════════════════════════════════════════════════════\n";

$client = App\Models\Client::where('email', 'calvinjunior921@gmail.com')->first();

if (!$client) {
    echo "❌ Client not found.\n";
    echo "   Looking for most recent client instead...\n\n";
    $client = App\Models\Client::orderBy('created_at', 'desc')->first();
}

if (!$client) {
    echo "❌ No clients found in database.\n\n";
    exit(1);
}

echo "✓ Found: " . $client->name . "\n";
echo "  Email: " . $client->email . "\n";
echo "  Registration: " . $client->registration_number . "\n\n";

// Get contractor
$contractor = App\Models\Contractor::where('user_id', $client->contractor_id)->first();

if (!$contractor) {
    echo "❌ Contractor not found.\n\n";
    exit(1);
}

echo "✓ Contractor: " . $contractor->company_name . " (" . $contractor->registration_number . ")\n\n";

// Ask what to do
echo "What would you like to do?\n";
echo "══════════════════════════════════════════════════════════\n";
echo "1. Resend invitation (keep current password)\n";
echo "2. Reset password and send new invitation\n";
echo "3. Just show current credentials\n";
echo "4. Cancel\n";
echo "Choose (1-4): ";

$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

if ($choice === '1') {
    // Resend
    echo "\n📧 Resending invitation with correct URL...\n";
    
    try {
        app(\App\Services\ClientInvitationService::class)
            ->resendInvitation($client, $contractor);
        
        echo "✓ Invitation resent to: " . $client->email . "\n\n";
        echo "╔══════════════════════════════════════════════════════════╗\n";
        echo "║                    EMAIL SENT!                           ║\n";
        echo "╚══════════════════════════════════════════════════════════╝\n";
        echo "Portal URL: " . $clientLoginUrl . "\n";
        echo "Email: " . $client->email . "\n";
        echo "Password: (Client's existing password)\n";
        echo "══════════════════════════════════════════════════════════\n\n";
        echo "✅ Client should check their email!\n";
        echo "   The URL in the email will now include the port number.\n\n";
    } catch (\Exception $e) {
        echo "❌ Failed: " . $e->getMessage() . "\n\n";
        exit(1);
    }
} elseif ($choice === '2') {
    // Reset password
    echo "\n🔐 Generating new password...\n";
    
    $newPassword = \Illuminate\Support\Str::random(12);
    $client->user->password = \Illuminate\Support\Facades\Hash::make($newPassword);
    $client->user->save();
    
    echo "✓ Password reset!\n";
    echo "  New Password: " . $newPassword . "\n\n";
    
    echo "📧 Sending invitation with new credentials...\n";
    
    try {
        $client->user->notify(new App\Notifications\ClientInvitation($client, $contractor, $newPassword));
        
        echo "✓ Email sent to: " . $client->email . "\n\n";
        echo "╔══════════════════════════════════════════════════════════╗\n";
        echo "║              NEW LOGIN CREDENTIALS                       ║\n";
        echo "╚══════════════════════════════════════════════════════════╝\n";
        echo "Portal URL: " . $clientLoginUrl . "\n";
        echo "Email: " . $client->email . "\n";
        echo "Password: " . $newPassword . "\n";
        echo "Registration: " . $client->registration_number . "\n";
        echo "══════════════════════════════════════════════════════════\n\n";
        echo "✅ Client should check their email!\n";
        echo "   The URL will now be correct with port number.\n\n";
    } catch (\Exception $e) {
        echo "❌ Failed to send email: " . $e->getMessage() . "\n";
        echo "\nBut password was changed! Manual credentials:\n";
        echo "══════════════════════════════════════════════════════════\n";
        echo "Portal: " . $clientLoginUrl . "\n";
        echo "Email: " . $client->email . "\n";
        echo "Password: " . $newPassword . "\n";
        echo "══════════════════════════════════════════════════════════\n\n";
        exit(1);
    }
} elseif ($choice === '3') {
    // Show info
    echo "\n╔══════════════════════════════════════════════════════════╗\n";
    echo "║                 CURRENT CLIENT INFO                      ║\n";
    echo "╚══════════════════════════════════════════════════════════╝\n";
    echo "Portal URL: " . $clientLoginUrl . "\n";
    echo "Email: " . $client->email . "\n";
    echo "Registration: " . $client->registration_number . "\n";
    echo "Contractor: " . $contractor->company_name . " (" . $contractor->registration_number . ")\n";
    echo "══════════════════════════════════════════════════════════\n\n";
    echo "Note: Client should use their existing password.\n";
    echo "      If they forgot it, run this script again and choose option 2.\n\n";
} else {
    echo "\n❌ Cancelled.\n\n";
    exit(0);
}

echo "═══════════════════════════════════════════════════════════\n";
echo "  NEXT STEPS:\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "1. Ask client to check email (and spam folder)\n";
echo "2. Client clicks button in email\n";
echo "3. Should redirect to: " . $clientLoginUrl . "\n";
echo "4. Client enters credentials and logs in\n\n";
echo "If URL still doesn't work:\n";
echo "- Double-check .env has correct APP_URL\n";
echo "- Run: php artisan config:clear\n";
echo "- See FIX_APP_URL.md for more help\n\n";
