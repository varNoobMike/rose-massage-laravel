<?php

namespace App\Actions\ActivityLog;

use App\Models\ActivityLog;
use App\Models\User;

class GetFilteredLogs
{
    public function execute(array $filters, string $role, $userId)
    {
        return ActivityLog::query()

            // 🔐 ROLE FILTER
            ->when($role === User::ROLE_RECEPTIONIST, function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })

            // 🔎 SEARCH
            ->when($filters['search'] ?? null, function ($q, $search) {

                $q->where(function ($inner) use ($search) {

                    $inner->where('message', 'like', "%{$search}%")
                          ->orWhere('subject_type', 'like', "%{$search}%")
                          ->orWhere('subject_id', 'like', "%{$search}%")
                          ->orWhereHas('user', function ($u) use ($search) {
                              $u->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('role', 'like', "%{$search}%")
                                ->orWhere('id', $search);
                          });

                });

            })

            // 🎯 ACTION
            ->when($filters['action'] ?? null, fn ($q, $action) =>
                $q->where('action', $action)
            )

            // 👤 USER FILTER
            ->when($filters['user_id'] ?? null, fn ($q, $id) =>
                $q->where('user_id', $id)
            )

            // 📦 SUBJECT TYPE
            ->when($filters['subject_type'] ?? null, fn ($q, $type) =>
                $q->where('subject_type', $type)
            )

            // 🧾 SUBJECT ID
            ->when($filters['subject_id'] ?? null, fn ($q, $id) =>
                $q->where('subject_id', $id)
            )

            // 📅 DATE FROM
            ->when($filters['from'] ?? null, fn ($q, $from) =>
                $q->whereDate('created_at', '>=', $from)
            )

            // 📅 DATE TO
            ->when($filters['to'] ?? null, fn ($q, $to) =>
                $q->whereDate('created_at', '<=', $to)
            )

            ->latest()
            ->paginate(20)
            ->withQueryString();
    }
}