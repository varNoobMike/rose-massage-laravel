<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $role = $this->currentUserRole();

        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_OWNER, User::ROLE_RECEPTIONIST])) {
            return to_route('dashboard');
        }

        $services = Service::active()->latest()->paginate(10);
        $reviews = Review::with(['user', 'images'])->where('status', 'approved')
            ->latest()
            ->paginate(10);
        return view('user.home', compact('services', 'reviews'));
    }
}
