<?php

namespace App\Actions\Review;

use App\Models\Review;
use App\Models\User;

class GetFilteredReviews
{
    public function execute(array $filters, string $userRole)
    {
        $search = $filters['search'] ?? null;
        $dateFrom = $filters['from'] ?? null;
        $dateTo = $filters['to'] ?? null;
        $rating = $filters['rating'] ?? null;
        $status = $filters['status'] ?? null;

        $query = Review::query()
            ->with(['user.profile', 'booking']); // eager load to avoid N+1

        // fetch only approved reviews if current user is client
        if($userRole === User::ROLE_CLIENT) {
            $query->where('status', 'approved');
        }

        // Search comment, booking id, user name, email, id
        $query->when($search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('comment', 'like', "%{$search}%")
                    ->orWhere('booking_id', $search)
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('id', $search);
                    });
            });
        });

        // Date from
        $query->when($dateFrom, function ($q, $date) {
            $q->whereDate('created_at', '>=', $date);
        });

        // Date to
        $query->when($dateTo, function ($q, $date) {
            $q->whereDate('created_at', '<=', $date);
        });

        // Rating
        $query->when($rating, function ($q, $rating) {
            if ($rating !== 'all') {
                $q->where('rating', $rating);
            }
        });

        // Status
        $query->when(
            $status,
            fn ($q, $status) => $q->where('status', $status)
        );

        // Final result
        return $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}