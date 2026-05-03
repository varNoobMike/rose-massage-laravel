<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NewBookingReviewNotification extends Notification
{
    public $review;

    public function __construct($review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database']; // or ['database','broadcast','mail']
    }

    public function toArray($notifiable)
    {
        $isClient = $notifiable->role === User::ROLE_CLIENT;

        return [
            'review_id' => $this->review->id,
            'booking_id' => $this->review->booking_id,
            'rating' => $this->review->rating,
            'comment' => $this->review->comment ?? null,

            'client_name' => $this->review->booking->client->name ?? null,

            'message' => $isClient
                ? 'Thank you for submitting your review.'
                : 'A new review has been submitted for a booking.',
        ];
    }
}