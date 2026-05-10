<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class StoreUser
{
    public function execute(array $data): array
    {
        $generatedPassword = Str::random(10);

        $user = DB::transaction(function () use ($data, $generatedPassword) {

            // create user
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($generatedPassword),
                'role'     => $data['role'],
                'status'   => $data['status'],
            ]);

            // default avatar
            $avatarPath = null;

            // upload avatar if exists
            if (($data['image'] ?? null) instanceof UploadedFile) {
                $avatarPath = $data['image']->store('user-profiles', 'public');
            }

            // create profile
            $user->profile()->create([
                'phone_number' => $data['phone_number'] ?? null,
                'address'      => $data['address'] ?? null,
                'gender'       => $data['gender'] ?? null,
                'birthdate'    => $data['birthdate'] ?? null,
                'avatar'       => $avatarPath,
            ]);

            return $user;
        });

        return [
            'user' => $user,
            'password' => $generatedPassword,
        ];
    }
}
