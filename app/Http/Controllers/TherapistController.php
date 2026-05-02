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

class TherapistController extends Controller
{

    public function index(Request $request, GetFilteredUsers $action)
    {
        $userRole = $this->currentUserRole();
        $filters = $request->only(['search', 'role', 'status']);

        $users = $action->execute($userRole, $filters, User::ROLE_THERAPIST);

        return view($this->currentRoleView() . '.therapists.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('profile');

        return view($this->currentRoleView() . '.therapists.show', compact('user'));
    }

    public function create()
    {        
        return view($this->currentRoleView() . '.therapists.create');
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

        $validated['role'] = User::ROLE_THERAPIST;

        $result = $action->execute($validated);

        $user = $result['user'];
        $password = $result['password'];

        return redirect()
            ->route('therapists.show', $user->id)
            ->with(
                'success',
                'Therapist record created successfully'
        );
    }


    public function edit(User $user)
    {
        return view($this->currentRoleView() . '.therapists.edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user, UpdateUser $action)
    {
        $validated = $request->validate([
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'name'   => 'required|string|max:255',
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

        $validated['role'] = User::ROLE_THERAPIST;

        $action->execute($user, $validated);

        return to_route('therapists.show', $user->id)
            ->with('success', 'Therapist record updated successfully.');
        
    }


}
