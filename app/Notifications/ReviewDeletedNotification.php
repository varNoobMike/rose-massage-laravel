<?php

namespace App\Notifications;

use App\Models\Review;
use App\Models\User;
use Illuminate\Notifications\Notification;

class ReviewDeletedNotification extends Notification
{
    public Review $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $isClient = $notifiable->role === User::ROLE_CLIENT;

        return [
            'review_id'   => $this->review->id,
            'booking_id'  => $this->review->booking_id,
            'rating'      => $this->review->rating,
            'comment'     => $this->review->comment ?? null,

            'client_name' => $this->review->booking->client->name ?? null,

            'message' => $isClient
                ? 'Your review has been removed.'
                : 'A review has been deleted.',
        ];
    }
}
