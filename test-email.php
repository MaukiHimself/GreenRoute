<?php

/**
 * EMAIL DIAGNOSTIC SCRIPT
 * Run this with: php test-email.php
 */

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

echo "\n";
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║         AFIA ORBIT - Email Configuration Test            ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Step 1: Check Configuration
echo "📋 Step 1: Checking Mail Configuration...\n";
echo str_repeat("-", 60) . "\n";

$mailer = Config::get('mail.default');
$host = Config::get('mail.mailers.smtp.host');
$port = Config::get('mail.mailers.smtp.port');
$username = Config::get('mail.mailers.smtp.username');
$password = Config::get('mail.mailers.smtp.password');
$encryption = Config::get('mail.mailers.smtp.encryption', 'tls');
$fromAddress = Config::get('mail.from.address');
$fromName = Config::get('mail.from.name');

echo "Mailer: " . ($mailer ?: '❌ NOT SET') . "\n";
echo "Host: " . ($host ?: '❌ NOT SET') . "\n";
echo "Port: " . ($port ?: '❌ NOT SET') . "\n";
echo "Username: " . ($username ?: '❌ NOT SET') . "\n";
echo "Password: " . ($password ? '✓ SET (' . strlen($password) . ' characters)' : '❌ NOT SET') . "\n";
echo "Encryption: " . ($encryption ?: '❌ NOT SET') . "\n";
echo "From Address: " . ($fromAddress ?: '❌ NOT SET') . "\n";
echo "From Name: " . ($fromName ?: '❌ NOT SET') . "\n";
echo "\n";

// Validation
$errors = [];
if ($mailer !== 'smtp') $errors[] = "MAIL_MAILER should be 'smtp', got: " . $mailer;
if ($host !== 'smtp.gmail.com') $errors[] = "MAIL_HOST should be 'smtp.gmail.com', got: " . $host;
if ($port != 587 && $port != 465) $errors[] = "MAIL_PORT should be 587 or 465, got: " . $port;
if (!$username) $errors[] = "MAIL_USERNAME is not set";
if (!$password) $errors[] = "MAIL_PASSWORD is not set";
if (strlen($password) !== 16 && $password) $errors[] = "Gmail app password should be 16 characters, got: " . strlen($password);

if (!empty($errors)) {
    echo "❌ CONFIGURATION ERRORS:\n";
    foreach ($errors as $error) {
        echo "   • " . $error . "\n";
    }
    echo "\n";
    echo "Fix these errors in your .env file, then run:\n";
    echo "   php artisan config:clear\n";
    echo "   php artisan cache:clear\n";
    echo "\n";
    exit(1);
}

echo "✅ Configuration looks good!\n\n";

// Step 2: Test Connection
echo "📧 Step 2: Testing Email Sending...\n";
echo str_repeat("-", 60) . "\n";

// Ask for recipient email
echo "Enter your email address to receive test email: ";
$handle = fopen("php://stdin", "r");
$recipientEmail = trim(fgets($handle));

if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Invalid email address!\n";
    exit(1);
}

echo "\nSending test email to: " . $recipientEmail . "\n";
echo "Please wait...\n\n";

try {
    Mail::raw('🎉 SUCCESS! Your AFIA ORBIT email configuration is working correctly!

This is a test email sent from your Laravel application.

Configuration Details:
- SMTP Host: ' . $host . '
- Port: ' . $port . '
- From: ' . $fromAddress . '
- Encryption: ' . strtoupper($encryption) . '

If you received this email, your Gmail SMTP setup is perfect!

Next Steps:
1. Test the ClientInvitation notification
2. Create clients with automatic email invitations
3. Enjoy automated email notifications!

---
AFIA ORBIT System
Automated at: ' . now()->format('Y-m-d H:i:s'), function ($message) use ($recipientEmail) {
        $message->to($recipientEmail)
                ->subject('✅ AFIA ORBIT - Email Test Successful');
    });

    echo "✅ Email sent successfully!\n\n";
    echo "📬 Check your inbox at: " . $recipientEmail . "\n";
    echo "   Also check your SPAM/Junk folder!\n\n";
    echo "If you received the email, your setup is complete! 🎉\n\n";

} catch (\Exception $e) {
    echo "❌ FAILED TO SEND EMAIL\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    
    echo "Common Solutions:\n";
    echo "1. Verify your Gmail app password is correct (16 characters, no spaces)\n";
    echo "2. Make sure 2-Factor Authentication is enabled on your Gmail account\n";
    echo "3. Try generating a new app password at: https://myaccount.google.com/apppasswords\n";
    echo "4. Clear cache: php artisan config:clear && php artisan cache:clear\n";
    echo "5. Check if port 587 is blocked by your firewall\n\n";
    
    echo "Full error details:\n";
    echo $e->getTraceAsString() . "\n";
    
    exit(1);
}

// Step 3: Test Notification (if data exists)
echo "\n📨 Step 3: Testing ClientInvitation Notification...\n";
echo str_repeat("-", 60) . "\n";

try {
    $contractor = App\Models\Contractor::first();
    $client = App\Models\Client::first();
    
    if (!$contractor || !$client) {
        echo "⚠️  Skipping - no test data available\n";
        echo "   Create a contractor and client first to test notifications\n\n";
    } else {
        echo "Sending ClientInvitation to: " . $recipientEmail . "\n";
        echo "Using test data:\n";
        echo "  • Client: " . $client->name . " (" . $client->registration_number . ")\n";
        echo "  • Contractor: " . $contractor->company_name . " (" . $contractor->registration_number . ")\n";
        echo "  • Temporary Password: TestPass123\n\n";
        
        \Notification::route('mail', $recipientEmail)
            ->notify(new App\Notifications\ClientInvitation($client, $contractor, 'TestPass123'));
        
        echo "✅ ClientInvitation notification sent!\n";
        echo "   Check your inbox for the professional invitation email\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Notification test failed: " . $e->getMessage() . "\n\n";
}

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                     TEST COMPLETE                         ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";
echo "Next: Open EMAIL_FIX_AND_TEST.md for more testing options\n";
echo "\n";
