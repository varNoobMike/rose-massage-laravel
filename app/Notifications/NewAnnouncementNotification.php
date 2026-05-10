<?php

namespace App\Notifications;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification
{
    public Announcement $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable)
    {
        return ['database']; // or ['database','broadcast','mail']
    }

    public function toArray($notifiable)
    {
        $isClient = $notifiable->role === User::ROLE_CLIENT;

        return [
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'type' => $this->announcement->type,
            'link_page' => $this->announcement->link_page,

            'message' => $isClient
                ? 'New announcement has been posted. Check it now for updates.'
                : 'A new announcement has been created. Please review it.',
        ];
    }
}
