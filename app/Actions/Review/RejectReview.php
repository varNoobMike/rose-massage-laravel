<?php

namespace App\Actions\Review;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReviewRejectedNotification;

class RejectReview
{
    public function execute(Review $review): Review
    {
        return DB::transaction(function () use ($review) {

            // update status
            $review->update([
                'status' => Review::STATUS_REJECTED,
            ]);

            /**
             * Send notification to the client
             */
            $recipient = User::where('id', $review->user_id)->get();

            Notification::send(
                $recipient,
                new ReviewRejectedNotification($review)
            );

            return $review;
        });
    }
}
