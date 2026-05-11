<?php

namespace App\Http\Controllers;

use App\Actions\User\GetFilteredUsers;
use App\Actions\User\StoreUser;
use App\Actions\User\UpdateUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionistController extends Controller
{
    public function index(Request $request, GetFilteredUsers $action)
    {

        $filters = $request->only([
            'search',
            'status',
        ]);

        // fetch filtered users
        $users = $action->execute(
            $filters,
            Auth::user(),
            User::ROLE_RECEPTIONIST
        );

        // basic filters state
        $hasBasicFilters =
            !empty($filters['search']) ||
            !empty($filters['role']) ||
            !empty($filters['status']);

        // global filters state
        $hasFilters = $hasBasicFilters;

        return view(
            $this->currentRoleView() . '.receptionists.index',
            compact('users', 'filters', 'hasFilters')
        );
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
            // profile
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

        return to_route('receptionists.show', $user->id)
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
            // profile
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
