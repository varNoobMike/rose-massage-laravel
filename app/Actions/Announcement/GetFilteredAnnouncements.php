<?php

namespace App\Actions\Announcement;

use App\Models\Announcement;
use App\Models\User;

class GetFilteredAnnouncements
{
    public function execute(array $filters, ?User $user = null)
    {
        $search = $filters['search'] ?? null;
        $type = $filters['type'] ?? null;
        $status = $filters['status'] ?? null;
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;

        $query = Announcement::query();

        // role check
        $isClient = !$user || $user->role === User::ROLE_CLIENT;

        // client: only active announcements
        if ($isClient) {
            $query->where('is_active', 1);
        }

        // search
        $query->when($search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {

                if (is_numeric($search)) {
                    $sub->where('id', (int) $search);
                }

                $sub->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        });

        // type filter
        $query->when($type, function ($q, $type) {
            $q->where('type', $type);
        });

        // status filter (admin/staff only)
        if (!$isClient) {
            $query->when($status, function ($q, $status) {
                $q->where('is_active', $status);
            });
        }

        // date from
        $query->when($dateFrom, function ($q, $dateFrom) {
            $q->whereDate('created_at', '>=', $dateFrom);
        });

        // date to
        $query->when($dateTo, function ($q, $dateTo) {
            $q->whereDate('created_at', '<=', $dateTo);
        });

        // final result
        return $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}
