<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'owner@gmail.com'],
            [
                'name' => 'Rose Massage Owner',
                'password' => Hash::make('owner123'),
                'role' => User::ROLE_OWNER,
            ]
        );
    }
}
