<?php

namespace App\Actions\User;

use App\Models\User;

class GetFilteredUsers
{
    public function execute(string $userRole, array $filters, ?string $roleFilter = null)
    {
        $search = $filters['search'] ?? null;
        $role   = $filters['role'] ?? null;
        $status = $filters['status'] ?? null;


        $query = User::query();

        // Exclude admin
        $query->where('role', '!=', User::ROLE_ADMIN);

        // Restrict therapist from filtering owner/receptionist
        if ($userRole === User::ROLE_RECEPTIONIST) {
            $query->whereNotIn('role', [
                User::ROLE_OWNER,
                User::ROLE_RECEPTIONIST,
            ]);
        }

        // Search
        $query->when($search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        });

        // Role
        $effectiveRole = $roleFilter ?? ($role ?? null);

        $query->when($effectiveRole && $effectiveRole !== 'all', function ($q) use ($effectiveRole) {
            $q->where('role', $effectiveRole);
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
