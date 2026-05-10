<?php

namespace App\Actions\ActivityLog;

use App\Models\ActivityLog;
use App\Models\User;

class GetFilteredLogs
{
    public function execute(
        array $filters,
        ?User $user = null
    ) {
        $search = $filters['search'] ?? null;
        $action = $filters['action'] ?? null;
        $subjectType = $filters['subject_type'] ?? null;
        $subjectId = $filters['subject_id'] ?? null;
        $from = $filters['date_from'] ?? null;
        $to = $filters['date_to'] ?? null;

        $query = ActivityLog::query();

        // receptionist can only see own logs
        if ($user?->role === User::ROLE_RECEPTIONIST) {
            $query->where('user_id', $user->id);
        }

        // search
        if (!empty($search)) {

            $query->where(function ($q) use ($search) {

                // search log message
                $q->where('message', 'like', "%{$search}%")

                    // search subject type
                    ->orWhere('subject_type', 'like', "%{$search}%")

                    // search subject id
                    ->orWhere('subject_id', 'like', "%{$search}%")

                    // search related user
                    ->orWhereHas('user', function ($u) use ($search) {

                        // search user name
                        $u->where('name', 'like', "%{$search}%")

                            // search user email
                            ->orWhere('email', 'like', "%{$search}%")

                            // search user role
                            ->orWhere('role', 'like', "%{$search}%");

                    });
            });
        }

        // action
        if (!empty($action)) {
            $query->where('action', $action);
        }

        // subject type
        if (!empty($subjectType)) {
            $query->where('subject_type', $subjectType);
        }

        // subject id
        if (!empty($subjectId)) {
            $query->where('subject_id', $subjectId);
        }

        // from date
        if (!empty($from)) {
            $query->whereDate('created_at', '>=', $from);
        }

        // to date
        if (!empty($to)) {
            $query->whereDate('created_at', '<=', $to);
        }

        // final result
        return $query
            ->latest()
            ->paginate(20)
            ->withQueryString();
    }
}
