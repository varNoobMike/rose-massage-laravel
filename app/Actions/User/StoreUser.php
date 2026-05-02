<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StoreUser
{
    public function execute(array $data): array
    {
        $generatedPassword = Str::random(10);

        $user = DB::transaction(function () use ($data, $generatedPassword) {

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($generatedPassword),
                'role'     => $data['role'],
                'status'   => $data['status'],
            ]);

            $avatar = $data['image'] ?? null;

            if ($avatar) {
                $avatar = $avatar->store('user-profiles', 'public');
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

        return [
            'user' => $user,
            'password' => $generatedPassword,
        ];

    }
}