<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role ?? '';

        if($role === 'admin' || $role === 'receptionist') {
            return redirect()->route('dashboard');
        }

        return view(
            'user.home',
            [
                'services' => Service::where('status', 'active')->latest()->paginate(10),
            ]
        );
    }
}