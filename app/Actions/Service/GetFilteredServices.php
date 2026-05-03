<?php

namespace App\Actions\Service;

use App\Models\Service;

class GetFilteredServices
{
    public function execute(array $filters)
    {
        
        $search = $filters['search'] ?? null;
        $priceFrom = $filters['price_from'] ?? null;
        $priceTo = $filters['price_to'] ?? null;
        $durationFrom = $filters['duration_from'] ?? null;
        $durationTo = $filters['duration_to'] ?? null;
        $status = $filters['status'] ?? null;

        $query = Service::query();

        // Search by name, id, or description
        $query->when($search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('id', $search)
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });

        // Price from
        $query->when($priceFrom, function ($q, $price) {
            $q->where('price', '>=', $price);
        });

        // Price to
        $query->when($priceTo, function ($q, $price) {
            $q->where('price', '<=', $price);
        });

        // Duration from
        $query->when($durationFrom, function ($q, $minutes) {
            $q->where('duration_minutes', '>=', $minutes);
        });

        // Duration to
        $query->when($durationTo, function ($q, $minutes) {
            $q->where('duration_minutes', '<=', $minutes);
        });

        // Status (default: active)
        if (!$status) {
            $query->where('status', 'active');
        } elseif ($status !== 'all') {
            $query->where('status', $status);
        }

        // Final result
        return $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}


