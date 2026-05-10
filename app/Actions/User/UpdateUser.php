<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UpdateUser
{
    public function execute(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            // existing avatar
            $avatarPath = $user->profile?->avatar;

            /**
             * handle avatar replacement
             */
            if (($data['image'] ?? null) instanceof UploadedFile) {

                // delete old avatar
                if ($user->profile?->avatar) {
                    Storage::disk('public')->delete($user->profile->avatar);
                }

                // store new avatar
                $avatarPath = $data['image']->store('user-profiles', 'public');
            }

            // update password if provided
            $password = $user->password;

            if (!empty($data['password'])) {
                $password = Hash::make($data['password']);
            }

            // update user
            $user->update([
                'email'    => $data['email'],
                'name'     => $data['name'],
                'role'     => $data['role'],
                'password' => $password,
                'status'   => $data['status'],
            ]);

            // ensure profile exists
            $profile = $user->profile ?: $user->profile()->create([]);

            // update profile
            $profile->update([
                'phone_number' => $data['phone_number'] ?? $profile->phone_number,
                'address'      => $data['address'] ?? $profile->address,
                'gender'       => $data['gender'] ?? $profile->gender,
                'birthdate'    => $data['birthdate'] ?? $profile->birthdate,
                'avatar'       => $avatarPath,
            ]);

            return $user;
        });
    }
}
