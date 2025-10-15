<?php

/**
 * Test Client Login - Comprehensive Debug
 * 
 * Usage: php test-client-login.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║           Test Client Login - Debug Report              ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Find client
$email = 'calvinjunior921@gmail.com';
echo "🔍 Testing client: $email\n";
echo "══════════════════════════════════════════════════════════\n";

$client = Client::where('email', $email)->first();
if (!$client) {
    echo "❌ Client not found\n\n";
    exit(1);
}

$user = $client->user;
if (!$user) {
    echo "❌ User account not found\n\n";
    exit(1);
}

echo "✓ Client: " . $client->name . "\n";
echo "✓ Registration: " . $client->registration_number . "\n";
echo "✓ User ID: " . $user->id . "\n\n";

// Check all the things
echo "📋 User Account Status\n";
echo "══════════════════════════════════════════════════════════\n";
echo "Email: " . $user->email . "\n";
echo "User Type: " . $user->user_type . "\n";
echo "Email Verified: " . ($user->email_verified_at ? 'Yes ✓ (' . $user->email_verified_at . ')' : 'No ✗') . "\n";
echo "Password Set: " . ($user->password ? 'Yes ✓' : 'No ✗') . "\n";
echo "Created: " . $user->created_at . "\n\n";

// Test authentication
echo "🔐 Testing Authentication\n";
echo "══════════════════════════════════════════════════════════\n";

$password = 'D69pTzSQ9Xr6'; // Current password
$canAuth = Auth::attempt(['email' => $email, 'password' => $password]);

if ($canAuth) {
    echo "✓ Authentication: SUCCESS\n";
    echo "✓ User can log in with provided password\n\n";
    Auth::logout(); // Clean up
} else {
    echo "❌ Authentication: FAILED\n";
    echo "   Password doesn't match or other auth issue\n\n";
}

// Check routes
echo "🛣️  Route Configuration\n";
echo "══════════════════════════════════════════════════════════\n";
echo "Login route: " . route('client.login') . "\n";
echo "Dashboard route: " . route('client.dashboard') . "\n\n";

// Check session config
echo "⚙️  Configuration\n";
echo "══════════════════════════════════════════════════════════\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "APP_ENV: " . config('app.env') . "\n";
echo "Session Driver: " . config('session.driver') . "\n";
echo "Session Lifetime: " . config('session.lifetime') . " minutes\n\n";

// Check middleware
echo "🛡️  Middleware Analysis\n";
echo "══════════════════════════════════════════════════════════\n";

$route = app('router')->getRoutes()->getByName('client.dashboard');
if ($route) {
    $middleware = $route->gatherMiddleware();
    echo "Dashboard middleware: " . implode(', ', $middleware) . "\n";
    
    if (in_array('verified', $middleware)) {
        echo "⚠️  WARNING: 'verified' middleware is still active!\n";
        echo "   This will cause redirect loop.\n";
    } else {
        echo "✓ No 'verified' middleware found\n";
    }
} else {
    echo "❌ Route 'client.dashboard' not found\n";
}
echo "\n";

// Summary
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║                     SUMMARY                              ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";

$issues = [];
$success = [];

if (!$user->email_verified_at) {
    $issues[] = "Email not verified";
} else {
    $success[] = "Email verified";
}

if ($user->user_type !== 'client') {
    $issues[] = "User type is '{$user->user_type}' (should be 'client')";
} else {
    $success[] = "User type is correct";
}

if (!$canAuth) {
    $issues[] = "Password doesn't match";
} else {
    $success[] = "Password is correct";
}

if ($route && in_array('verified', $route->gatherMiddleware())) {
    $issues[] = "Dashboard still requires verified middleware";
} else {
    $success[] = "No verification required for dashboard";
}

if (count($success) > 0) {
    echo "✅ Working:\n";
    foreach ($success as $item) {
        echo "   • $item\n";
    }
    echo "\n";
}

if (count($issues) > 0) {
    echo "⚠️  Issues Found:\n";
    foreach ($issues as $item) {
        echo "   • $item\n";
    }
    echo "\n";
    echo "🔧 Fixes needed:\n";
    if (in_array("Email not verified", $issues)) {
        echo "   • Run: php fix-client-login-issue.php\n";
    }
    if (strpos(implode(' ', $issues), "verified middleware") !== false) {
        echo "   • Clear caches: php artisan cache:clear && php artisan route:clear\n";
    }
    echo "\n";
} else {
    echo "🎉 Everything looks good!\n\n";
    echo "If login still fails:\n";
    echo "1. Clear browser cookies/cache (or use incognito)\n";
    echo "2. Check browser console for errors (F12)\n";
    echo "3. Check Laravel logs: storage/logs/laravel.log\n";
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════\n";
echo "  LOGIN CREDENTIALS\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "URL: " . url('/client/login') . "\n";
echo "Email: $email\n";
echo "Password: $password\n";
echo "═══════════════════════════════════════════════════════════\n\n";
