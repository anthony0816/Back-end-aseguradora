<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@riskcontrol.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'trader@riskcontrol.com'],
            [
                'name' => 'Trader User',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );
    }
}
