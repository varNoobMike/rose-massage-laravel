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

class ClientController extends Controller
{

    public function index(Request $request, GetFilteredUsers $action)
    {
        $userRole = $this->currentUserRole();
        $filters = $request->only(['search', 'role', 'status']);

        $users = $action->execute($userRole, $filters, User::ROLE_CLIENT);

        return view($this->currentRoleView() . '.clients.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('profile');

        return view($this->currentRoleView() . '.clients.show', compact('user'));
    }

}
