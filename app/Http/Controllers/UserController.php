<?php

namespace App\Http\Controllers;

use App\Actions\User\GetFilteredUsers;
use App\Actions\User\StoreUser;
use App\Actions\User\UpdateUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request, GetFilteredUsers $action)
    {
        $filters = $request->only([
            'search',
            'role',
            'status',
        ]);

        // fetch filtered users
        $users = $action->execute(
            $filters,
            Auth::user()
        );

        // basic filters state
        $hasBasicFilters =
            !empty($filters['search']) ||
            !empty($filters['role']) ||
            !empty($filters['status']);

        // global filters state
        $hasFilters = $hasBasicFilters;

        return view(
            $this->currentRoleView() . '.users.index',
            compact('users', 'filters', 'hasFilters')
        );
    }

    public function show(User $user)
    {
        $user->load('profile');

        return view(
            $this->currentRoleView() . '.users.show',
            compact('user')
        );
    }

    public function create()
    {
        return view($this->currentRoleView() . '.users.create');
    }

    public function store(Request $request, StoreUser $action)
    {

        $validated = $request->validate([
            'email'        => 'required|email|max:255|unique:users,email',
            'name'         => 'required|string|max:255',
            'role'         => 'required|string|in:owner,receptionist,therapist,client',
            'status'       => 'required|in:active,inactive',
            // profiles
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'gender'       => 'nullable|in:male,female,other',
            'birthdate'    => 'nullable|date|before:today',
            'image'        => 'nullable|image|max:2048',
        ]);


        $result = $action->execute($validated);

        $user = $result['user'];
        $password = $result['password'];

        return redirect()
            ->route('users.show', $user->id)
            ->with(
                'success',
                $user->role === User::ROLE_THERAPIST
                    ? 'Therapist record created successfully'
                    : "User account created successfully. Temporary password: {$password}"
            );
    }

    public function edit(User $user)
    {
        $user->load('profile');

        return view($this->currentRoleView() . '.users.edit', compact('user'));
    }


    public function update(Request $request, User $user, UpdateUser $action)
    {

        $validated = $request->validate([
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'name'   => 'required|string|max:255',
            'role'   => 'required|string|in:owner,receptionist,therapist,client',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
            // profiles
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'gender'       => 'nullable|in:male,female,other',
            'birthdate'    => 'nullable|date|before:today',
            'image'        => 'nullable|image|max:2048',
        ]);

        $prevRole = $user->role;

        $userUpdated = $action->execute($user, $validated);
        $newRole = $userUpdated->role;

        return to_route('users.show', $user->id)
            ->with(
                'info',
                $newRole !== $prevRole
                    ? "User updated successfully. Note: The role has been changed to {$newRole}, which will affect the user's access to the system."
                    : "User updated successfully."
            );
    }
}
