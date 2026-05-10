<?php

namespace App\Actions\Review;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReviewDeletedNotification;

class DestroyReview
{
    public function execute(Review $review, int $userId): void
    {
        DB::transaction(function () use ($review, $userId) {

            // load relations
            $review->load('images', 'booking.client');

            
            /**
             * Send notification to the client first before delete
             */
            $recipient = User::where('id', $review->user_id)->get();

            Notification::send(
                $recipient,
                new ReviewDeletedNotification($review)
            );

            // delete images from storage
            foreach ($review->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            // delete image records
            $review->images()->delete();

            // delete review
            $review->delete();
        });
    }
}
