<?php

namespace App\Notifications;

use App\Models\Client;
use App\Models\Contractor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientPendingApprovalNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Client $client,
        protected Contractor $contractor
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $approvalUrl = url('/contractor/pending-clients');

        return (new MailMessage)
            ->subject('New Client Registration Pending Approval — ' . config('app.name'))
            ->greeting('Hello ' . $this->contractor->company_name . '!')
            ->line('A new client has registered through the self-registration portal and is awaiting your approval.')
            ->line('')
            ->line('**Client Details:**')
            ->line('**Name:** ' . $this->client->name)
            ->line('**Contact:** ' . $this->client->contact_name)
            ->line('**Email:** ' . $this->client->email)
            ->line('**Phone:** ' . $this->client->phone)
            ->line('**Location:** ' . implode(', ', array_filter([
                $this->client->street,
                $this->client->ward,
                $this->client->district,
                $this->client->region,
            ])))
            ->line('**Requested Route:** ' . $this->client->route)
            ->line('**Category:** ' . $this->client->category)
            ->line('')
            ->action('Review & Approve Client', $approvalUrl)
            ->line('Please log in to your contractor dashboard to approve or reject this registration.')
            ->line('Thank you for using ' . config('app.name') . '!');
    }
}
