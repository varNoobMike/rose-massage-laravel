<?php

namespace App\Actions\Review;

use App\Models\Review;
use Illuminate\Http\UploadedFile;  // maybe remove this line or improve in the future
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateReview
{
    public function execute(
        array $validated,
        Review $review,
        int $userId,
        array $existingImageIds = [],
        array $newImages = []
    ): Review {

        return DB::transaction(function () use (
            $validated,
            $review,
            $userId,
            $existingImageIds,
            $newImages
        ) {

            // Ensure user owns the review
            if ($review->user_id !== $userId) {
                throw new \Exception('Unauthorized action.');
            }

            // Update review data
            $review->update([
                'rating'  => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            // Get images to delete (not in existing list)
            $imagesToDelete = $review->images()
                ->when($existingImageIds, function ($q) use ($existingImageIds) {
                    $q->whereNotIn('id', $existingImageIds);
                })
                ->get();

            // Remove images from storage and DB
            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }

            // Store new uploaded images
            foreach ($newImages as $image) {
                if (!$image instanceof UploadedFile) continue;

                $path = $image->store('reviews', 'public');

                $review->images()->create([
                    'path' => $path,
                ]);
            }

            // Return updated review with images
            return $review->fresh(['images']);
        });
    }
}