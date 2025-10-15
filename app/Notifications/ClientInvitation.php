<?php

namespace App\Notifications;

use App\Models\Client;
use App\Models\Contractor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientInvitation extends Notification
{
    use Queueable;

    protected $client;
    protected $contractor;
    protected $temporaryPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct(Client $client, Contractor $contractor, $temporaryPassword = null)
    {
        $this->client = $client;
        $this->contractor = $contractor;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        // Use specific client login URL
        $portalUrl = url('/client/login');
        
        $message = (new MailMessage)
            ->subject('Welcome to ' . config('app.name') . ' - Client Portal Access')
            ->greeting('Hello ' . $this->client->name . '!')
            ->line('You have been added as a client by **' . $this->contractor->company_name . '**.')
            ->line('')
            ->line('### Your Account Details:')
            ->line('**Registration Number:** ' . $this->client->registration_number)
            ->line('**Email:** ' . $this->client->email)
            ->line('**Contractor:** ' . $this->contractor->company_name . ' (' . $this->contractor->registration_number . ')');

        if ($this->temporaryPassword) {
            $message->line('')
                    ->line('### Login Credentials:')
                    ->line('**Email:** ' . $this->client->email)
                    ->line('**Temporary Password:** `' . $this->temporaryPassword . '`')
                    ->line('')
                    ->line('⚠️ Please save these credentials in a secure location.')
                    ->line('💡 You can change your password after your first login.');
        }

        $message->line('')
                ->action('Access Client Portal', $portalUrl)
                ->line('')
                ->line('### Through the portal, you can:')
                ->line('✓ View all invoices from your contractor')
                ->line('✓ Check scheduled pickups and services')
                ->line('✓ Download invoice PDFs')
                ->line('✓ Track your service history')
                ->line('✓ Request additional services')
                ->line('')
                ->line('---')
                ->line('If you have any questions, please contact **' . $this->contractor->company_name . '** directly.')
                ->line('Thank you for choosing ' . config('app.name') . '!');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'client_id' => $this->client->id,
            'client_registration_number' => $this->client->registration_number,
            'contractor_id' => $this->contractor->id,
            'contractor_registration_number' => $this->contractor->registration_number,
        ];
    }
}
