<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UpdateUser
{
    public function execute(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            // password update (optional)
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // update user
            $user->update([
                'email'    => $data['email'],
                'name'     => $data['name'],
                'role'     => $data['role'],
                'password' => $data['password'] ?? $user->password,
                'status'   => $data['status'],
            ]);

            // profile
            $profile = $user->profile ?: $user->profile()->create([]);

            // image already normalized from controller
            if (!empty($data['image'])) {

                if ($profile->avatar) {
                    Storage::disk('public')->delete($profile->avatar);
                }

                $data['image'] = $data['image']->store('user-profiles', 'public');
            }

            $profile->update([
                'phone_number' => $data['phone_number'] ?? $profile->phone_number,
                'address'      => $data['address'] ?? $profile->address,
                'gender'       => $data['gender'] ?? $profile->gender,
                'birthdate'    => $data['birthdate'] ?? $profile->birthdate,
                'avatar'       => $data['image'] ?? $profile->avatar,
            ]);

            return $user;
        });
    }
}