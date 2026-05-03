<?php

namespace App\Actions\Report;

use App\Models\Booking;

class GetBookingReport
{
    public function execute(array $filters = [])
    {
        $query = Booking::with(['client', 'items.service'])

            // 🔍 SEARCH
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('id', $search)
                        ->orWhereHas('client', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            })

            // 📅 FROM
            ->when($filters['from'] ?? null, function ($q, $from) {
                $q->whereDate('created_at', '>=', $from);
            })

            // 📅 TO
            ->when($filters['to'] ?? null, function ($q, $to) {
                $q->whereDate('created_at', '<=', $to);
            })

            // 📌 STATUS
            ->when($filters['status'] ?? null, function ($q, $status) {
                $q->where('status', $status);
            })

            // 💰 MIN
            ->when($filters['min_amount'] ?? null, function ($q, $min) {
                $q->where('total_amount', '>=', $min);
            })

            // 💰 MAX
            ->when($filters['max_amount'] ?? null, function ($q, $max) {
                $q->where('total_amount', '<=', $max);
            });

        // 🔁 SORTING
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';

        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortBy, $sortDir);

        return $query;
    }
}