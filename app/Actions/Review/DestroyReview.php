<?php

namespace App\Actions\Review;

use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DestroyReview
{
    public function execute(Review $review, int $userId): void
    {
        DB::transaction(function () use ($review, $userId) {

            // Load related images
            $review->load('images');

            // Delete images from storage
            foreach ($review->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            // Delete image records and review
            $review->images()->delete();
            $review->delete();
        });
    }
}
