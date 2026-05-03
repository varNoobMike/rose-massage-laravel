<?php

namespace App\Actions\Review;

use App\Models\Review;

class GetFilteredReviews
{
    public function execute(array $filters)
    {
        $query = Review::query()
            ->with(['user.profile', 'booking']); // eager load to avoid N+1

        /**
         * SEARCH
         * - comment
         * - booking id
         * - user name/email
         */
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('comment', 'like', "%{$search}%")
                    ->orWhere('booking_id', $search)
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        });

        /**
         * DATE FILTER
         */
        $query->when($filters['date'] ?? null, function ($q, $date) {
            $q->whereDate('created_at', $date);
        });

        /**
         * RATING FILTER
         */
        $query->when($filters['rating'] ?? null, function ($q, $rating) {
            if ($rating !== 'all') {
                $q->where('rating', $rating);
            }
        });

        /**
         * STATUS FILTER
         */
        $query->when(
            $filters['status'] ?? null,
            fn ($q, $status) => $q->where('status', $status)
        );

        /**
         * SORT (latest first)
         */
        return $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}