<?php

namespace App\Actions\Review;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBookingReviewNotification;

class StoreReview
{
    public function execute(
        array $data,
        Booking $booking,
        User $user
    ): Review {

        return DB::transaction(function () use ($data, $booking, $user) {

            // ensure owner
            if ($booking->client_id !== $user->id) {
                throw new \Exception('Unauthorized action.');
            }

            // prevent duplicate review
            $exists = Review::where('booking_id', $booking->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($exists) {
                throw new \Exception('You already reviewed this booking.');
            }

            // create review
            $review = Review::create([
                'user_id'    => $user->id,
                'booking_id' => $booking->id,
                'rating'     => $data['rating'],
                'comment'    => $data['comment'],
                'status'     => Review::STATUS_PENDING
            ]);

            // upload images (now inside $data like your pattern)
            if (!empty($data['images'])) {

                foreach ($data['images'] as $image) {

                    if ($image instanceof UploadedFile) {

                        $path = $image->store('reviews', 'public');

                        $review->images()->create([
                            'path' => $path,
                        ]);
                    }
                }
            }

            /**
             * Send notification to admin, staff and the client
             */
            $recipients = User::whereIn('role', [
                User::ROLE_ADMIN,
                User::ROLE_OWNER
            ])->get();

            $recipients->push($booking->client);

            Notification::send(
                $recipients->unique('id'),
                new NewBookingReviewNotification($review)
            );

            return $review;
        });
    }
}
