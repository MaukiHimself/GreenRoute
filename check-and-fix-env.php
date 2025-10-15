<?php

/**
 * Check and Fix .env APP_URL
 * 
 * Usage: php check-and-fix-env.php
 */

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║            Check and Fix .env APP_URL                   ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "❌ .env file not found!\n\n";
    exit(1);
}

echo "📄 Reading .env file...\n";
$envContent = file_get_contents($envFile);
$lines = explode("\n", $envContent);

$currentAppUrl = null;
$appUrlLineNumber = null;

foreach ($lines as $index => $line) {
    if (strpos($line, 'APP_URL=') === 0) {
        $currentAppUrl = trim(substr($line, 8));
        $appUrlLineNumber = $index;
        break;
    }
}

echo "══════════════════════════════════════════════════════════\n";
if ($currentAppUrl) {
    echo "Current APP_URL: " . $currentAppUrl . "\n\n";
    
    // Check if it has port
    if (!preg_match('/:\d+/', $currentAppUrl) && !preg_match('/https?:\/\/[^\/]+\.[a-z]+/', $currentAppUrl)) {
        echo "⚠️  WARNING: APP_URL is missing port number!\n";
        echo "   This causes email links to not work.\n\n";
        
        echo "🔧 Fix it now? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $choice = trim(fgets($handle));
        
        if (strtolower($choice) === 'y') {
            // Update the line
            $newAppUrl = $currentAppUrl . ':8000';
            $lines[$appUrlLineNumber] = 'APP_URL=' . $newAppUrl;
            
            // Write back to file
            file_put_contents($envFile, implode("\n", $lines));
            
            echo "✓ Updated!\n";
            echo "  Old: " . $currentAppUrl . "\n";
            echo "  New: " . $newAppUrl . "\n\n";
            
            echo "🔄 Clearing configuration cache...\n";
            system('php artisan config:clear');
            system('php artisan cache:clear');
            
            echo "\n✅ Fixed! APP_URL now includes port :8000\n\n";
            
            echo "╔══════════════════════════════════════════════════════════╗\n";
            echo "║                IMPORTANT NEXT STEP                       ║\n";
            echo "╚══════════════════════════════════════════════════════════╝\n";
            echo "You must RESEND the invitation email to your client!\n\n";
            echo "Run this command:\n";
            echo "  php fix-url-and-resend.php\n\n";
            echo "Or manually:\n";
            echo "  php artisan tinker\n";
            echo "  Then resend invitation\n\n";
        } else {
            echo "❌ Cancelled.\n";
            echo "\nTo fix manually:\n";
            echo "1. Open .env file\n";
            echo "2. Find: APP_URL=$currentAppUrl\n";
            echo "3. Change to: APP_URL=$currentAppUrl:8000\n";
            echo "4. Run: php artisan config:clear\n";
            echo "5. Resend email to client\n\n";
        }
    } else {
        echo "✅ APP_URL looks correct (has port or domain)\n\n";
        
        echo "Current URL: $currentAppUrl\n\n";
        
        echo "💡 If email still has wrong URL:\n";
        echo "1. The old email was sent before the fix\n";
        echo "2. You need to resend the invitation\n\n";
        
        echo "Run: php fix-url-and-resend.php\n\n";
    }
} else {
    echo "❌ APP_URL not found in .env file!\n\n";
    
    echo "Add it manually:\n";
    echo "1. Open .env file\n";
    echo "2. Add line: APP_URL=http://localhost:8000\n";
    echo "3. Run: php artisan config:clear\n\n";
}

echo "═══════════════════════════════════════════════════════════\n\n";
