<?php

/**
 * QUICK FIX: Send Invitation to Recently Created Client
 * 
 * Run this script to create user account and send invitation email
 * to the client you just created.
 * 
 * Usage: php send-invitation-to-recent-client.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;
use App\Models\Contractor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ClientInvitation;

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║     Send Invitation to Recently Created Client          ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Get the most recent client
echo "🔍 Finding your most recent client...\n\n";

$client = Client::orderBy('created_at', 'desc')->first();

if (!$client) {
    echo "❌ No clients found in the database.\n";
    echo "   Please create a client first through the contractor dashboard.\n\n";
    exit(1);
}

echo "✓ Found client:\n";
echo "  Name: " . $client->name . "\n";
echo "  Email: " . $client->email . "\n";
echo "  Registration: " . $client->registration_number . "\n";
echo "  Created: " . $client->created_at->format('Y-m-d H:i:s') . "\n\n";

// Check if this is the correct client
echo "Is this the client you want to send invitation to? (y/n): ";
$handle = fopen("php://stdin", "r");
$confirm = trim(fgets($handle));

if (strtolower($confirm) !== 'y') {
    echo "\n❌ Cancelled. Please specify the client email:\n";
    echo "   Email: ";
    $email = trim(fgets($handle));
    
    $client = Client::where('email', $email)->first();
    
    if (!$client) {
        echo "❌ Client not found with email: $email\n\n";
        exit(1);
    }
    
    echo "✓ Found: " . $client->name . " (" . $client->registration_number . ")\n\n";
}

// Get contractor
$contractor = Contractor::where('user_id', $client->contractor_id)->first();

if (!$contractor) {
    echo "❌ Contractor not found for this client.\n\n";
    exit(1);
}

echo "✓ Contractor: " . $contractor->company_name . " (" . $contractor->registration_number . ")\n\n";

// Check if client already has user account
if ($client->user_id) {
    $user = User::find($client->user_id);
    echo "ℹ️  Client already has a user account.\n";
    echo "   Email: " . $user->email . "\n\n";
    
    echo "What would you like to do?\n";
    echo "  1. Resend invitation (keep current password)\n";
    echo "  2. Reset password and send new credentials\n";
    echo "  3. Cancel\n";
    echo "Choose (1-3): ";
    
    $choice = trim(fgets($handle));
    
    if ($choice === '1') {
        // Resend invitation
        echo "\n📧 Resending invitation email...\n";
        
        try {
            app(\App\Services\ClientInvitationService::class)
                ->resendInvitation($client, $contractor);
            
            echo "✓ Invitation email resent to: " . $client->email . "\n";
            echo "\nNote: Client should use their existing password.\n";
            echo "If they forgot it, run this script again and choose option 2.\n\n";
        } catch (\Exception $e) {
            echo "❌ Failed to send email: " . $e->getMessage() . "\n\n";
            echo "Check your email configuration in .env file.\n";
            echo "See EMAIL_FIX_AND_TEST.md for help.\n\n";
            exit(1);
        }
    } elseif ($choice === '2') {
        // Reset password
        echo "\n🔐 Generating new password...\n";
        
        $newPassword = \Illuminate\Support\Str::random(12);
        $user->password = Hash::make($newPassword);
        $user->save();
        
        echo "✓ Password reset successfully!\n";
        echo "  New Password: " . $newPassword . "\n\n";
        
        echo "📧 Sending email with new credentials...\n";
        
        try {
            $user->notify(new ClientInvitation($client, $contractor, $newPassword));
            
            echo "✓ Email sent to: " . $client->email . "\n\n";
            echo "╔══════════════════════════════════════════════════════════╗\n";
            echo "║                  NEW LOGIN CREDENTIALS                   ║\n";
            echo "╚══════════════════════════════════════════════════════════╝\n";
            echo "Portal URL: " . url('/client/login') . "\n";
            echo "Email: " . $client->email . "\n";
            echo "Password: " . $newPassword . "\n";
            echo "Registration: " . $client->registration_number . "\n";
            echo "══════════════════════════════════════════════════════════\n\n";
        } catch (\Exception $e) {
            echo "❌ Failed to send email: " . $e->getMessage() . "\n\n";
            echo "But password was changed! Manual credentials:\n";
            echo "══════════════════════════════════════════════════════════\n";
            echo "Email: " . $client->email . "\n";
            echo "Password: " . $newPassword . "\n";
            echo "Portal: " . url('/client/login') . "\n";
            echo "══════════════════════════════════════════════════════════\n";
            echo "\nSend these credentials to your client manually.\n\n";
        }
    } else {
        echo "\n❌ Cancelled.\n\n";
        exit(0);
    }
} else {
    // Create new user account
    echo "⚠️  Client doesn't have a user account yet.\n";
    echo "   Creating user account and sending invitation...\n\n";
    
    $tempPassword = \Illuminate\Support\Str::random(12);
    
    try {
        // Create user
        $user = User::create([
            'name' => $client->name,
            'email' => $client->email,
            'password' => Hash::make($tempPassword),
            'user_type' => 'client',
        ]);
        
        echo "✓ User account created!\n";
        
        // Link to client
        $client->user_id = $user->id;
        $client->save();
        
        echo "✓ Linked user to client record\n\n";
        
        // Send invitation email
        echo "📧 Sending invitation email...\n";
        
        $user->notify(new ClientInvitation($client, $contractor, $tempPassword));
        
        echo "✓ Invitation email sent to: " . $client->email . "\n\n";
        
        echo "╔══════════════════════════════════════════════════════════╗\n";
        echo "║              CLIENT LOGIN CREDENTIALS                    ║\n";
        echo "╚══════════════════════════════════════════════════════════╝\n";
        echo "Portal URL: " . url('/client/login') . "\n";
        echo "Email: " . $client->email . "\n";
        echo "Password: " . $tempPassword . "\n";
        echo "Registration: " . $client->registration_number . "\n";
        echo "══════════════════════════════════════════════════════════\n\n";
        echo "✅ Done! Client should receive invitation email shortly.\n";
        echo "   Also check spam folder.\n\n";
        
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n\n";
        
        // If email failed but user created, show credentials
        if (isset($user) && isset($tempPassword)) {
            echo "User account was created but email failed to send.\n";
            echo "Manual credentials:\n";
            echo "══════════════════════════════════════════════════════════\n";
            echo "Email: " . $client->email . "\n";
            echo "Password: " . $tempPassword . "\n";
            echo "Portal: " . url('/client/login') . "\n";
            echo "══════════════════════════════════════════════════════════\n";
            echo "\nSend these credentials to your client manually.\n";
            echo "\nTo fix email issues, see EMAIL_FIX_AND_TEST.md\n\n";
        }
        
        exit(1);
    }
}

echo "═══════════════════════════════════════════════════════════\n";
echo "  NEXT STEPS:\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "1. Ask client to check their email (and spam folder)\n";
echo "2. Client logs in at: " . url('/client/login') . "\n";
echo "3. Future clients will receive invitations automatically\n\n";
echo "Need help? See CLIENT_INVITATION_FIX.md\n\n";
