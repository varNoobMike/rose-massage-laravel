<?php

namespace App\Actions\Review;

use App\Models\Review;
use App\Models\User;

class GetFilteredReviews
{
    public function execute(
        array $filters,
        ?User $user = null
    ) {

        $search = $filters['search'] ?? null;
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;
        $rating = $filters['rating'] ?? null;
        $status = $filters['status'] ?? null;

        $query = Review::query()
            ->with([
                'user.profile',
                'booking',
            ]);

        // role check
        $isClient = !$user || $user->role === User::ROLE_CLIENT;

        // clients can only see approved reviews
        if ($isClient) {
            $query->where('status', Review::STATUS_APPROVED);
        }

        // search
        $query->when($search, function ($q, $search) use ($isClient) {

            $q->where(function ($sub) use ($search, $isClient) {

                // client search
                if ($isClient) {

                    $sub->where('comment', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        });

                    return;
                }

                // admin/staff search
                $sub->where('comment', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $sub->orWhere('id', (int) $search)
                        ->orWhere('booking_id', (int) $search);
                }

                $sub->orWhereHas('user', function ($user) use ($search) {

                    $user->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");

                    if (is_numeric($search)) {
                        $user->orWhere('id', (int) $search);
                    }
                });
            });
        });

        // date from
        $query->when($dateFrom, function ($q, $dateFrom) {
            $q->whereDate('created_at', '>=', $dateFrom);
        });

        // date to
        $query->when($dateTo, function ($q, $dateTo) {
            $q->whereDate('created_at', '<=', $dateTo);
        });

        // rating
        $query->when($rating, function ($q, $rating) {

            if ($rating !== 'all') {
                $q->where('rating', $rating);
            }
        });

        // status rules
        if (!$isClient) {
            $query->when($status, function ($q, $status) {
                $q->where('status', $status);
            });
        }

        // final result
        return $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}
