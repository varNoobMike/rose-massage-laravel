<?php

namespace App\Actions\User;

use App\Models\User;

class GetFilteredUsers
{
    public function execute(string $userRole, array $filters, ?string $roleFilter = null)
    {
        $query = User::query();

        // Always exclude admin
        $query->where('role', '!=', User::ROLE_ADMIN);

        /**
         * Role-based restrictions
         */
        if ($userRole === User::ROLE_RECEPTIONIST) {
            $query->whereNotIn('role', [
                User::ROLE_OWNER,
                User::ROLE_RECEPTIONIST,
            ]);
        }

        $search = $filters['search'] ?? null;
        $role   = $filters['role'] ?? null;
        $status = $filters['status'] ?? null;

        /**
         * Search
         */
        $query->when($search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        });

        /**
         * Role filter
         */
        $effectiveRole = $roleFilter ?? ($filters['role'] ?? null);

        $query->when($effectiveRole && $effectiveRole !== 'all', function ($q) use ($effectiveRole) {
            $q->where('role', $effectiveRole);
        }); 

        /**
         * Status filter
         */
        $query->when($status, function ($q, $status) {
            return match ($status) {
                'active' => $q->where('status', 'active'),
                'inactive' => $q->where('status', 'inactive'),
                default => $q,
            };
        }, function ($q) {
            $q->where('status', 'active');
        });

        return $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}