<?php

/**
 * Fix Session Redirect Loop Issue
 * 
 * Usage: php fix-session-issue.php
 */

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║         Fix Session Redirect Loop                       ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "❌ .env file not found!\n\n";
    exit(1);
}

echo "📋 Checking session configuration...\n";
echo "══════════════════════════════════════════════════════════\n";

$envContent = file_get_contents($envFile);
$lines = explode("\n", $envContent);

$sessionDriver = null;
$sessionDomain = null;
$sessionSecureCookie = null;
$sessionSameSite = null;

foreach ($lines as $line) {
    if (strpos($line, 'SESSION_DRIVER=') === 0) {
        $sessionDriver = trim(substr($line, 15));
    }
    if (strpos($line, 'SESSION_DOMAIN=') === 0) {
        $sessionDomain = trim(substr($line, 15));
    }
    if (strpos($line, 'SESSION_SECURE_COOKIE=') === 0) {
        $sessionSecureCookie = trim(substr($line, 22));
    }
    if (strpos($line, 'SESSION_SAME_SITE=') === 0) {
        $sessionSameSite = trim(substr($line, 18));
    }
}

echo "SESSION_DRIVER: " . ($sessionDriver ?: 'file (default)') . "\n";
echo "SESSION_DOMAIN: " . ($sessionDomain ?: 'null (default)') . "\n";
echo "SESSION_SECURE_COOKIE: " . ($sessionSecureCookie ?: 'false (default)') . "\n";
echo "SESSION_SAME_SITE: " . ($sessionSameSite ?: 'lax (default)') . "\n\n";

// Check for issues
$issues = [];
$fixes = [];

if ($sessionSecureCookie === 'true') {
    $issues[] = "SESSION_SECURE_COOKIE is set to 'true' but you're using http://localhost (not https)";
    $fixes[] = "Change SESSION_SECURE_COOKIE=false";
}

if ($sessionDomain && $sessionDomain !== 'null' && $sessionDomain !== 'localhost') {
    $issues[] = "SESSION_DOMAIN is set to '$sessionDomain' which might not match localhost";
    $fixes[] = "Change SESSION_DOMAIN=null or remove the line";
}

if ($sessionSameSite === 'strict') {
    $issues[] = "SESSION_SAME_SITE=strict might cause issues";
    $fixes[] = "Change SESSION_SAME_SITE=lax";
}

if (count($issues) > 0) {
    echo "⚠️  ISSUES FOUND:\n";
    echo "══════════════════════════════════════════════════════════\n";
    foreach ($issues as $issue) {
        echo "• " . $issue . "\n";
    }
    echo "\n";
    
    echo "🔧 RECOMMENDED FIXES:\n";
    echo "══════════════════════════════════════════════════════════\n";
    foreach ($fixes as $fix) {
        echo "• " . $fix . "\n";
    }
    echo "\n";
    
    echo "Apply fixes automatically? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $choice = trim(fgets($handle));
    
    if (strtolower($choice) === 'y') {
        // Apply fixes
        $newLines = [];
        $addedSessionDriver = false;
        
        foreach ($lines as $line) {
            if (strpos($line, 'SESSION_SECURE_COOKIE=') === 0) {
                $newLines[] = 'SESSION_SECURE_COOKIE=false';
            } elseif (strpos($line, 'SESSION_DOMAIN=') === 0) {
                $newLines[] = 'SESSION_DOMAIN=null';
            } elseif (strpos($line, 'SESSION_SAME_SITE=') === 0) {
                $newLines[] = 'SESSION_SAME_SITE=lax';
            } elseif (strpos($line, 'SESSION_DRIVER=') === 0) {
                $newLines[] = $line; // Keep as is
                $addedSessionDriver = true;
            } else {
                $newLines[] = $line;
            }
        }
        
        file_put_contents($envFile, implode("\n", $newLines));
        
        echo "✓ Applied fixes to .env\n\n";
        
        echo "🔄 Clearing caches...\n";
        system('php artisan config:clear');
        system('php artisan cache:clear');
        system('php artisan session:clear 2>&1');
        
        echo "\n✅ Fixed!\n\n";
        echo "IMPORTANT: Clear browser cookies or use incognito mode!\n\n";
    }
} else {
    echo "✅ Session configuration looks okay.\n\n";
    
    echo "💡 The redirect loop might be caused by:\n";
    echo "══════════════════════════════════════════════════════════\n";
    echo "1. Browser cookies from old sessions\n";
    echo "2. Middleware in ClientPortalController\n";
    echo "3. Database sessions table full/corrupted\n\n";
    
    echo "🔧 Try these fixes:\n";
    echo "══════════════════════════════════════════════════════════\n";
    echo "1. Clear browser cookies (Ctrl+Shift+Delete) or use incognito\n";
    echo "2. Clear Laravel sessions:\n";
    echo "   php artisan session:clear\n";
    echo "3. Truncate sessions table:\n";
    echo "   php artisan tinker\n";
    echo "   DB::table('sessions')->truncate();\n\n";
}

echo "═══════════════════════════════════════════════════════════\n";
echo "  NEXT STEPS:\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "1. IMPORTANT: Clear browser cookies/cache or use incognito\n";
echo "2. Try logging in again\n";
echo "3. If still fails, check browser console (F12) for errors\n\n";
