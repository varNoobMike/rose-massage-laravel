<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification
{
    public array $announcement;

    public function __construct(array $announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'announcement_id' => $this->announcement['id'] ?? null,
            'title' => $this->announcement['title'] ?? null,
            'type' => $this->announcement['type'] ?? null,

            'message' => 'New announcement: ' . ($this->announcement['title'] ?? 'Update available'),
        ];
    }
}
