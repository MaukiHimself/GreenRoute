<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Lightweight, reusable in-app notification used by the bell in both the
 * client and contractor portals. Stored on the `database` channel only so it
 * shows up in the bell dropdown without sending email/SMS.
 *
 * Usage:
 *   $user->notify(new GenericNotification(
 *       title: 'New message',
 *       message: 'Acme Ltd sent you a message',
 *       url: route('sms.conversation', $client),
 *       icon: 'bi-chat-dots',
 *   ));
 */
class GenericNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public ?string $url = null,
        public string $icon = 'bi-bell',
    ) {
    }

    /**
     * Deliver to the database channel so it appears in the bell.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'icon' => $this->icon,
        ];
    }
}
