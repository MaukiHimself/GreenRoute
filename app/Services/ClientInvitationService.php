<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Contractor;
use App\Models\User;
use App\Notifications\ClientInvitation;
use Illuminate\Support\Facades\DB;
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
        // Create the client first so we always preserve the contractor's client record.
        $client = Client::create($clientData);
        $user = null;
        $temporaryPassword = null;

        if ($createUserAccount && isset($clientData['email'])) {
            $temporaryPassword = Str::random(12); // Generate random password

            try {
                DB::transaction(function () use ($client, $contractor, $clientData, &$user, $temporaryPassword) {
                    $user = User::create([
                        'name' => $client->name,
                        'email' => $client->email,
                        'password' => Hash::make($temporaryPassword),
                        'user_type' => 'client',
                        'email_verified_at' => now(), // Auto-verify invited clients
                    ]);

                    $client->user_id = $user->id;
                    $client->save();

                    $user->notify(new ClientInvitation($client, $contractor, $temporaryPassword));
                });
            } catch (\Exception $e) {
                \Log::error('Failed to create client invitation account or send email: ' . $e->getMessage(), [
                    'client_id' => $client->id,
                    'client_email' => $client->email,
                ]);

                $user = null;
                $temporaryPassword = null;
            }
        } elseif (isset($clientData['email'])) {
            try {
                \Notification::route('mail', $client->email)
                    ->notify(new ClientInvitation($client, $contractor, null));
            } catch (\Exception $e) {
                \Log::error('Failed to send client invitation email: ' . $e->getMessage(), [
                    'client_id' => $client->id,
                    'client_email' => $client->email,
                ]);
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
