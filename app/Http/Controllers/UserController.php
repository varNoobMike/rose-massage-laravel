<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Start query
        $query = User::query();

        // exclude admin
        $query->whereNot('role', User::ROLE_ADMIN);

        // if current role is receptionist exclude owner, and receptionist, but this block of code needs future improvement
        if($this->currentUserRole() === User::ROLE_RECEPTIONIST) {
            $query->whereNot('role', User::ROLE_OWNER);
            $query->whereNot('role', User::ROLE_RECEPTIONIST);
        }

        // 1. Search by Name, Email, or ID
        $query->when($request->search, function ($q, $search) {
            return $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        });

        // 2. Filter by Role
        $query->when($request->role, function ($q, $role) {
            return match ($role) {
                User::ROLE_ADMIN => $q->where('role', User::ROLE_ADMIN),
                User::ROLE_OWNER => $q->where('role', User::ROLE_OWNER),
                User::ROLE_CLIENT => $q->where('role', User::ROLE_CLIENT),
                User::ROLE_THERAPIST => $q->where('role', User::ROLE_THERAPIST),
                User::ROLE_RECEPTIONIST => $q->where('role', User::ROLE_RECEPTIONIST),
                'all' => $q,
                default => $q,
            };
        });

        // 3. Filter by Status
        $query->when($request->status, function ($q, $status) {
            return match ($status) {
                'active' => $q->where('status', 'active'),
                'inactive' => $q->where('status', 'inactive'),
                'all' => $q,
                default => $q,
            };
        }, function ($q) {
            // default when nothing selected
            return $q->where('status', 'active');
        });

        // Execute query
        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            $this->currentRoleView() . '.users.index',
            ['users' => $users]
        );
    }

    public function show(User $user)
    {
        return view($this->currentRoleView() . '.users.show', [
            'user' => $user
        ]);
    }

    public function create()
    {
        return view($this->currentRoleView() . '.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email'        => 'required|email|max:255|unique:users,email',
            'name'         => 'required|string|max:255',
            'role'         => 'required|string|in:owner,receptionist,therapist,client',
            'status'       => 'required|in:active,inactive',

            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'gender'       => 'nullable|in:male,female,other',
            'birthdate'    => 'nullable|date|before:today',
            'image'        => 'nullable|image|max:2048',
        ]);

        try {

            $generatedPassword = Str::random(10);

            $user = DB::transaction(function () use ($request, $data, $generatedPassword) {

                $user = User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => Hash::make($generatedPassword),
                    'role'     => $data['role'],
                    'status'   => $data['status'],
                ]);

                $avatar = null;

                if ($request->hasFile('image')) {
                    $avatar = $request->file('image')->store('user-profiles', 'public');
                }

                $user->profile()->create([
                    'phone_number' => $data['phone_number'] ?? null,
                    'address'      => $data['address'] ?? null,
                    'gender'       => $data['gender'] ?? null,
                    'birthdate'    => $data['birthdate'] ?? null,
                    'avatar'       => $avatar,
                ]);

                return $user;
            });

            return redirect()
                ->route('users.show', $user->id)
                ->with('success', $user->role === User::ROLE_THERAPIST ? 'Therapist record created successfully' : "User account created successfully. Temporary password: {$generatedPassword}");
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create user.');
        }
    }

    public function edit(User $user)
    {
        return view($this->currentRoleView() . '.users.edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'name'   => 'required|string|max:255',
            'role'   => 'required|string|in:owner,receptionist,therapist,client',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',

            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'gender'       => 'nullable|in:male,female,other',
            'birthdate'    => 'nullable|date|before:today',
            'image'        => 'nullable|image|max:2048',
        ]);

        try {

            DB::transaction(function () use ($request, $data, $user) {

                // update password only if provided
                if (!empty($data['password'])) {
                    $data['password'] = Hash::make($data['password']);
                }

                $user->update([
                    'email'    => $data['email'],
                    'name'     => $data['name'],
                    'role'     => $data['role'],
                    'password' => $data['password'] ?? $user->password,
                    'status'   => $data['status'],
                ]);

                $profile = $user->profile ?: $user->profile()->create([]);

                if ($request->hasFile('image')) {

                    if ($profile->avatar) {
                        Storage::disk('public')->delete($profile->avatar);
                    }

                    $data['image'] = $request->file('image')
                        ->store('user-profiles', 'public');
                }

                $profile->update([
                    'phone_number' => $data['phone_number'] ?? $profile->phone_number,
                    'address'      => $data['address'] ?? $profile->address,
                    'gender'       => $data['gender'] ?? $profile->gender,
                    'birthdate'    => $data['birthdate'] ?? $profile->birthdate,
                    'avatar'       => $data['image'] ?? $profile->avatar,
                ]);
            });

            return redirect()
                ->route('users.show', $user->id)
                ->with('success', 'User updated successfully.');
        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', 'Update failed.');
        }
    }
}
