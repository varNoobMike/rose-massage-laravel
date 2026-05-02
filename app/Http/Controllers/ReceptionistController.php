<?php

namespace App\Http\Controllers;

use App\Actions\User\GetFilteredUsers;
use App\Actions\User\StoreUser;
use App\Actions\User\UpdateUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReceptionistController extends Controller
{
    public function index(Request $request, GetFilteredUsers $action)
    {
        $userRole = $this->currentUserRole();
        $filters = $request->only(['search', 'role', 'status']);

        $users = $action->execute($userRole, $filters, User::ROLE_RECEPTIONIST);

        return view($this->currentRoleView() . '.receptionists.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('profile');

        return view($this->currentRoleView() . '.receptionists.show', compact('user'));
    }

    public function create()
    {        
        return view($this->currentRoleView() . '.receptionists.create');
    }

    public function store(Request $request, StoreUser $action)
    {
        $validated = $request->validate([
            'email'        => 'required|email|max:255|unique:users,email',
            'name'         => 'required|string|max:255',
            'status'       => 'required|in:active,inactive',
            /*
                profile
            */
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'gender'       => 'nullable|in:male,female,other',
            'birthdate'    => 'nullable|date|before:today',
            'image'        => 'nullable|image|max:2048',
        ]);

        $validated['role'] = User::ROLE_RECEPTIONIST;

        $result = $action->execute($validated);

        $user = $result['user'];
        $password = $result['password'];

        return redirect()
            ->route('users.show', $user->id)
            ->with(
                'success',
                "Receptionist account created successfully. Temporary password: {$password}"
            );
    }

    public function edit(User $user)
    {
        $user->load('profile');

        return view($this->currentRoleView() . '.receptionists.edit', compact('user'));
    }


    public function update(Request $request, User $user, UpdateUser $action)
    {
        $validated = $request->validate([
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'name'   => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
            /*
                profile
            */
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'gender'       => 'nullable|in:male,female,other',
            'birthdate'    => 'nullable|date|before:today',
            'image'        => 'nullable|image|max:2048',
        ]);

        $validated['role'] = User::ROLE_RECEPTIONIST;

        $action->execute($user, $validated);

        return to_route('receptionists.show', $user->id)
            ->with('success', 'Receptionist account updated successfully.');

        
    }


    
}
