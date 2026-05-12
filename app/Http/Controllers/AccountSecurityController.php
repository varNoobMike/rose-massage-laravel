<?php

namespace App\Http\Controllers;

use App\Actions\AccountSecurity\UpdatePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountSecurityController extends Controller
{
    /**
     * Show security dashboard
     */
    public function index()
    {
        $user = Auth::user();
        return view($this->currentRoleView() . '.account.security', compact('user'));
    }

    /**
     * Show change password form
     */
    public function editPassword()
    {
        return view($this->currentRoleView() . '.account.password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request, UpdatePassword $action)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $action->execute(Auth::user(), $validated);

        Auth::logoutOtherDevices($validated['password']);

        return to_route('account.security')
            ->with('success', 'Password updated successfully.');
    }
}
