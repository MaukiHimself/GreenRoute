<?php

/**
 * Fix Client Login Redirect Loop
 * 
 * This script checks and fixes the email verification issue
 * 
 * Usage: php fix-client-login-issue.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;
use App\Models\User;

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║         Fix Client Login Redirect Loop                  ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Find the client
echo "🔍 Checking client: calvinjunior921@gmail.com\n";
echo "══════════════════════════════════════════════════════════\n";

$client = Client::where('email', 'calvinjunior921@gmail.com')->first();

if (!$client) {
    echo "❌ Client not found.\n\n";
    exit(1);
}

echo "✓ Found: " . $client->name . "\n";
echo "  Registration: " . $client->registration_number . "\n\n";

// Check user account
if (!$client->user_id) {
    echo "❌ Client doesn't have a linked user account.\n\n";
    exit(1);
}

$user = User::find($client->user_id);

if (!$user) {
    echo "❌ User account not found.\n\n";
    exit(1);
}

echo "✓ User account found\n";
echo "  Email: " . $user->email . "\n";
echo "  User Type: " . $user->user_type . "\n";
echo "  Email Verified: " . ($user->email_verified_at ? 'Yes ✓' : 'No ✗') . "\n\n";

// Check the issue
if (!$user->email_verified_at) {
    echo "⚠️  ISSUE FOUND: Email Not Verified\n";
    echo "══════════════════════════════════════════════════════════\n";
    echo "Your client routes require 'verified' middleware,\n";
    echo "but this user's email is not verified.\n\n";
    echo "This causes a redirect loop:\n";
    echo "1. Client logs in ✓\n";
    echo "2. Redirects to dashboard\n";
    echo "3. Dashboard requires verified email ✗\n";
    echo "4. Redirects back to login\n";
    echo "5. Loop continues...\n\n";
    
    echo "🔧 FIX OPTIONS:\n";
    echo "══════════════════════════════════════════════════════════\n";
    echo "1. Mark email as verified (Recommended for invited clients)\n";
    echo "2. Show current status only\n";
    echo "3. Cancel\n";
    echo "Choose (1-3): ";
    
    $handle = fopen("php://stdin", "r");
    $choice = trim(fgets($handle));
    
    if ($choice === '1') {
        echo "\n📧 Marking email as verified...\n";
        
        $user->email_verified_at = now();
        $user->save();
        
        echo "✓ Email marked as verified!\n\n";
        
        echo "╔══════════════════════════════════════════════════════════╗\n";
        echo "║                    FIXED!                                ║\n";
        echo "╚══════════════════════════════════════════════════════════╝\n";
        echo "User: " . $user->email . "\n";
        echo "Email Verified: Yes ✓\n";
        echo "══════════════════════════════════════════════════════════\n\n";
        
        echo "✅ Client can now login successfully!\n";
        echo "   Try logging in again at: " . url('/client/login') . "\n\n";
        
        echo "Login Credentials:\n";
        echo "Email: calvinjunior921@gmail.com\n";
        echo "Password: D69pTzSQ9Xr6\n\n";
        
    } elseif ($choice === '2') {
        echo "\n╔══════════════════════════════════════════════════════════╗\n";
        echo "║                   STATUS REPORT                          ║\n";
        echo "╚══════════════════════════════════════════════════════════╝\n";
        echo "Issue: Email not verified\n";
        echo "Impact: Login redirect loop\n";
        echo "Solution: Mark email as verified or remove verified middleware\n";
        echo "══════════════════════════════════════════════════════════\n\n";
    } else {
        echo "\n❌ Cancelled.\n\n";
        exit(0);
    }
} else {
    echo "✅ Email is already verified!\n\n";
    echo "The redirect loop might be caused by another issue.\n";
    echo "Let me check other potential problems...\n\n";
    
    // Check routes
    echo "📋 Checking route configuration...\n";
    echo "══════════════════════════════════════════════════════════\n";
    
    echo "Dashboard route: " . route('client.dashboard') . "\n";
    echo "Login route: " . route('client.login') . "\n\n";
    
    echo "💡 Other possible causes:\n";
    echo "1. Session driver issue (check .env SESSION_DRIVER)\n";
    echo "2. Middleware blocking access\n";
    echo "3. User type mismatch\n\n";
    
    echo "Current user type: " . $user->user_type . "\n";
    if ($user->user_type !== 'client') {
        echo "⚠️  WARNING: User type should be 'client' but is '" . $user->user_type . "'\n\n";
        echo "Fix user type? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $fix = trim(fgets($handle));
        if (strtolower($fix) === 'y') {
            $user->user_type = 'client';
            $user->save();
            echo "✓ User type fixed!\n\n";
        }
    }
}

echo "═══════════════════════════════════════════════════════════\n";
echo "  NEXT STEPS:\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "1. Clear browser cookies and cache\n";
echo "2. Try logging in again at: " . url('/client/login') . "\n";
echo "3. Use credentials:\n";
echo "   Email: calvinjunior921@gmail.com\n";
echo "   Password: D69pTzSQ9Xr6\n\n";
echo "If still having issues, check:\n";
echo "- Browser console for errors\n";
echo "- Laravel logs: storage/logs/laravel.log\n";
echo "- Session driver in .env\n\n";
