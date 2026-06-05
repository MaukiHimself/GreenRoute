<?php

namespace App\Notifications;

use App\Models\Client;
use App\Models\Contractor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Client $client,
        protected Contractor $contractor,
        protected string $temporaryPassword
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $loginUrl = url('/client/login');

        return (new MailMessage)
            ->subject('Your GreenRoute Account Has Been Approved!')
            ->greeting('Hello ' . $this->client->name . '!')
            ->line('Great news — your registration with **' . $this->contractor->company_name . '** has been approved.')
            ->line('')
            ->line('### Your Login Credentials:')
            ->line('**Email:** ' . $this->client->email)
            ->line('**Temporary Password:** `' . $this->temporaryPassword . '`')
            ->line('**Registration Number:** ' . $this->client->registration_number)
            ->line('')
            ->line('⚠️ Please change your password after your first login.')
            ->line('')
            ->action('Login to Client Portal', $loginUrl)
            ->line('')
            ->line('### What you can do in the portal:')
            ->line('✓ View your pickup schedules')
            ->line('✓ Check and pay invoices')
            ->line('✓ Request additional services')
            ->line('✓ Contact your contractor')
            ->line('')
            ->line('---')
            ->line('If you have any questions, contact **' . $this->contractor->company_name . '** directly.')
            ->line('Thank you for choosing ' . config('app.name') . '!');
    }
}
