<?php

namespace App\Actions\Service;

use App\Models\Service;
use App\Models\User;

class GetFilteredServices
{
    public function execute(array $filters, ?User $user = null)
    {
        $search = $filters['search'] ?? null;
        $priceFrom = $filters['price_from'] ?? null;
        $priceTo = $filters['price_to'] ?? null;
        $durationFrom = $filters['duration_from'] ?? null;
        $durationTo = $filters['duration_to'] ?? null;
        $status = $filters['status'] ?? null;

        $query = Service::query();

        // role check
        $isClient = !$user || $user->role === User::ROLE_CLIENT;

        // search
        $query->when($search, function ($q, $search) use ($isClient) {
            $q->where(function ($sub) use ($search, $isClient) {

                // client: only search by name
                if ($isClient) {
                    $sub->where('name', 'like', "%{$search}%");
                    return;
                }

                // admin/staff: search by id and name
                if (is_numeric($search)) {
                    $sub->orWhere('id', (int) $search);
                }

                $sub->orWhere('name', 'like', "%{$search}%");
            });
        });

        // price from
        $query->when($priceFrom, function ($q, $priceFrom) {
            $q->where('price', '>=', $priceFrom);
        });

        // price to
        $query->when($priceTo, function ($q, $priceTo) {
            $q->where('price', '<=', $priceTo);
        });

        // duration from
        $query->when($durationFrom, function ($q, $durationFrom) {
            $q->where('duration_minutes', '>=', $durationFrom);
        });

        // duration to
        $query->when($durationTo, function ($q, $durationTo) {
            $q->where('duration_minutes', '<=', $durationTo);
        });

        // status rules
        if ($isClient) {
            // clients can only see active services
            $query->where('status', Service::STATUS_ACTIVE);
        } else {
            // admin/staff can filter by status
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
