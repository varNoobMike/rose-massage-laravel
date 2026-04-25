<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database']; // or ['database','broadcast','mail']
    }

    public function toArray($notifiable)
    {
        $isClient = $notifiable->role === User::ROLE_CLIENT;

        return [
            'booking_id' => $this->booking->id,
            'booking_date' => $this->booking->booking_date,
            'client_name' => $this->booking->client->name ?? null,

            'message' => $isClient
                ? 'Your booking has been successfully created.'
                : 'A new booking has been created by a client.',
        ];
    }
}
