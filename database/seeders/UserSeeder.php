<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Administrator',
                'email' => 'admin@sepatu.id',
                'role' => 'admin',
            ],
            [
                'name' => 'Pelanggan Store',
                'email' => 'user@sepatu.id',
                'role' => 'user',
            ],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Hash::make('password'),
                    'role' => $account['role'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}