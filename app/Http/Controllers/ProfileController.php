<?php

namespace App\Http\Controllers;

use App\Actions\User\UpdateUserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view($this->currentRoleView() . '.profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view($this->currentRoleView() . '.profile.edit', compact('user'));
    }

    public function update(Request $request, UpdateUserProfile $action)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            // profiles
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'gender'       => 'nullable|in:male,female,other',
            'birthdate'    => 'nullable|date|before:today',
            'image'        => 'nullable|image|max:2048',
        ]);

        $action->execute(Auth::user(), $validated);

        return to_route('profile.index')
            ->with('success', 'Profile updated successfully.');
    }

    public function security()
    {
        $user = Auth::user();
        return view($this->currentRoleView() . '.profile.security', compact('user'));
    }
}
