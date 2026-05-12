<?php

namespace App\Actions\AccountSecurity;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdatePassword
{
    public function execute(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        });
    }
}
