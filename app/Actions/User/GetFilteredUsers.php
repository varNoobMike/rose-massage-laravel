<?php

namespace App\Actions\User;

use App\Models\User;

class GetFilteredUsers
{
    public function execute(
        array $filters,
        ?User $user = null,
        ?string $specificRole = null
    ) {
        $search = $filters['search'] ?? null;
        $role   = $filters['role'] ?? null;
        $status = $filters['status'] ?? null;

        $query = User::query();

        // always exclude admin
        $query->where('role', '!=', User::ROLE_ADMIN);

        // role restrictions based on current user
        if ($user?->role === User::ROLE_RECEPTIONIST) {
            $query->whereNotIn('role', [
                User::ROLE_OWNER,
                User::ROLE_RECEPTIONIST,
            ]);
        }

        // force specific role 
        if ($specificRole) {
            $query->where('role', $specificRole);
        }

        // search users by name, email, or id
        $query->when($search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {

                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        });

        // role filter (only if no specificRole AND not empty)
        if (!$specificRole && !empty($role)) {
            $query->where('role', $role);
        }

        // status filter
        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}
