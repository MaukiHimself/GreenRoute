<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Contractor;
use App\Models\User;
use App\Notifications\ClientInvitation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientInvitationService
{
    /**
     * Create a client and send invitation email
     * 
     * @param array $clientData
     * @param Contractor $contractor
     * @param bool $createUserAccount
     * @return array ['client' => Client, 'user' => User|null, 'password' => string|null]
     */
    public function createClientWithInvitation(array $clientData, Contractor $contractor, bool $createUserAccount = true)
    {
        // Create the client
        $client = Client::create($clientData);
        
        $user = null;
        $temporaryPassword = null;

        // Create user account if requested
        if ($createUserAccount && isset($clientData['email'])) {
            $temporaryPassword = Str::random(12); // Generate random password
            
            $user = User::create([
                'name' => $client->name,
                'email' => $client->email,
                'password' => Hash::make($temporaryPassword),
                'user_type' => 'client',
                'email_verified_at' => now(), // Auto-verify invited clients
            ]);

            // Link user to client
            $client->user_id = $user->id;
            $client->save();
        }

        // Send invitation email
        if (isset($clientData['email'])) {
            try {
                // You can send to the user if exists, or directly to email
                if ($user) {
                    $user->notify(new ClientInvitation($client, $contractor, $temporaryPassword));
                } else {
                    // Send to email address directly without user account
                    \Notification::route('mail', $client->email)
                        ->notify(new ClientInvitation($client, $contractor, null));
                }
            } catch (\Exception $e) {
                // Log error but don't fail the client creation
                \Log::error('Failed to send client invitation email: ' . $e->getMessage());
            }
        }

        return [
            'client' => $client,
            'user' => $user,
            'password' => $temporaryPassword,
        ];
    }

    /**
     * Resend invitation to existing client
     * 
     * @param Client $client
     * @param Contractor $contractor
     * @return bool
     */
    public function resendInvitation(Client $client, Contractor $contractor)
    {
        if (!$client->email) {
            return false;
        }

        try {
            if ($client->user) {
                $client->user->notify(new ClientInvitation($client, $contractor));
            } else {
                \Notification::route('mail', $client->email)
                    ->notify(new ClientInvitation($client, $contractor));
            }
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to resend client invitation: ' . $e->getMessage());
            return false;
        }
    }
}
