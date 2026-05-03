<?php

namespace App\Actions\Review;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBookingReviewNotification;
use Illuminate\Support\Facades\DB;

class StoreReview
{
    public function execute(array $validated, array $images, Booking $booking, $user): Review
    {
        return DB::transaction(function () use ($validated, $images, $booking, $user) {

            // Ensure only booking owner can review
            if ($booking->client_id !== $user->id) {
                throw new \Exception('Unauthorized action.');
            }

            // Prevent duplicate review for same booking
            if (
                Review::where('booking_id', $booking->id)
                ->where('user_id', $user->id)
                ->exists()
            ) {
                throw new \Exception('Already reviewed this booking.');
            }

            // Create review record
            $review = Review::create([
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            // Upload and attach images
            foreach ($images as $image) {
                $path = $image->store('reviews', 'public');

                $review->images()->create([
                    'path' => $path,
                ]);
            }

            // Send notifications (still inside transaction, needs future improvement ex: put notification outside transaction or use queue)
            $recipients = User::whereIn('role', [
                User::ROLE_ADMIN,
                User::ROLE_OWNER,
                User::ROLE_RECEPTIONIST,
            ])->get();

            // include the owner only
            $recipients->push($booking->client);

            Notification::send(
                $recipients->unique('id'),
                new NewBookingReviewNotification($review)
            );

            return $review;
        });
    }
}
