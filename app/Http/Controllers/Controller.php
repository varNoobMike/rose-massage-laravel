<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    public function currentUser()
    {
        return Auth::user();
    }

    public function currentUserRole()
    {
        return $this->currentUser()->role;
    }

    public function currentRoleView()
    {
        $role = $this->currentUser()->role;

        return match ($role) {
            'admin', 'owner', 'receptionist' => 'admin',
            'client' => 'user',
            default => 'user',
        };
    }
    
}
