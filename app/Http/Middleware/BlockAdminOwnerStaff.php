<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockAdminOwnerStaff
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Allow guests (not logged in users)
        if (!$user) {
            return $next($request);
        }

        // Block admin and owner
        if (in_array($user->role, ['admin', 'owner', 'receptionist'])) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
