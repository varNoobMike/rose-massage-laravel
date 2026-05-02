<?php

namespace App\Actions\Review;

use App\Models\Booking;
use App\Models\Review;
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

            return $review;
        });
    }
}
