<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
{
    public function __construct(
        public Booking $booking,
        public string $status // confirmed, rejected, cancelled, updated
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $isClient = $notifiable->role === User::ROLE_CLIENT;

        return [
            'booking_id'   => $this->booking->id,
            'status'       => $this->status,
            'booking_date' => $this->booking->booking_date,

            'message'      => $this->getMessage($isClient),
        ];
    }

    private function getMessage(bool $isClient)
    {
        return match ($this->status) {

            'confirmed' => $isClient
                ? 'Your booking request has been confirmed.'
                : 'You confirmed a booking.',

            'rejected' => $isClient
                ? 'Your booking request has been rejected.'
                : 'You rejected a booking.',

            'cancelled' => $isClient
                ? 'Your booking request has been cancelled.'
                : 'A booking was cancelled.',

            'completed' => $isClient
                ? 'Your booking session at spa has been completed.'
                : 'A booking has been completed.',

            default => 'Booking status updated.',
        };
    }
}
