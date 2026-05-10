<?php

namespace App\Http\Controllers;

use App\Actions\User\GetFilteredUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
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
            User::ROLE_CLIENT
        );

        // basic filters state
        $hasBasicFilters =
            !empty($filters['search']) ||
            !empty($filters['role']) ||
            !empty($filters['status']);

        // global filters state
        $hasFilters = $hasBasicFilters;

        return view(
            $this->currentRoleView() . '.clients.index',
            compact('users', 'filters', 'hasFilters')
        );
    }

    public function show(User $user)
    {
        $user->load('profile');
        return view($this->currentRoleView() . '.clients.show', compact('user'));
    }
}
