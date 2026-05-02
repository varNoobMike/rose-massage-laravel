<?php

namespace App\Actions\Service;

use App\Models\Service;

class GetFilteredServices
{
    public function execute(array $filters)
    {
        $query = Service::query();

        // Search by name, id, or description
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('id', $search)
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });

        // Filter by price range
        $query->when($filters['rate'] ?? null, function ($q, $price) {
            match ($price) {
                'low'  => $q->where('price', '<', 1500),
                'mid'  => $q->whereBetween('price', [1500, 3000]),
                'high' => $q->where('price', '>', 3000),
                default => null,
            };
        });

        // Filter by duration
        $query->when($filters['duration'] ?? null, function ($q, $duration) {
            match ($duration) {
                'short' => $q->where('duration_minutes', '<', 60),
                '60'    => $q->where('duration_minutes', 60),
                '90'    => $q->where('duration_minutes', 90),
                'long'  => $q->where('duration_minutes', '>', 90),
                default => null,
            };
        });

        // Filter by status (default: active)
        $query->when(
            $filters['status'] ?? null,
            fn($q, $status) => $status === 'all'
                ? $q
                : $q->where('status', $status),
            fn($q) => $q->where('status', 'active')
        );

        // Final result
        return $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}


