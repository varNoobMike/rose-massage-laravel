<?php

namespace App\Actions\Review;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateReview
{
    public function execute(
        array $data,
        Review $review,
        User $user
    ): Review {

        return DB::transaction(function () use ($data, $review, $user) {

            // ensure owner
            if ($review->user_id !== $user->id) {
                throw new \Exception('Unauthorized action.');
            }

            // update review
            $review->update([
                'rating'  => $data['rating'],
                'comment' => $data['comment'],
            ]);

            /**
             * Handle existing images removal
             */
            $existingImageIds = $data['existing_images'] ?? [];

            $imagesToDelete = $review->images()
                ->when($existingImageIds, function ($q) use ($existingImageIds) {
                    $q->whereNotIn('id', $existingImageIds);
                })
                ->get();

            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }

            /**
             * Upload new images
             */
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

            return $review->fresh(['images']);
        });
    }
}
