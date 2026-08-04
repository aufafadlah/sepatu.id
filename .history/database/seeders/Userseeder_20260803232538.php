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
            ['name' => 'Super Admin', 'email' => 'superadmin@sepatu-id.test', 'role' => 'super_admin'],
            ['name' => 'Admin Produk', 'email' => 'adminproduk@sepatu-id.test', 'role' => 'admin_produk'],
            ['name' => 'Admin Gudang', 'email' => 'admingudang@sepatu-id.test', 'role' => 'admin_gudang'],
            ['name' => 'Admin Pesanan', 'email' => 'adminpesanan@sepatu-id.test', 'role' => 'admin_pesanan'],
            ['name' => 'Pelanggan Contoh', 'email' => 'user@sepatu-id.test', 'role' => 'user'],
        ];

        foreach ($accounts as $account) {
            User::create([
                'name' => $account['name'],
                'email' => $account['email'],
                'password' => Hash::make('password'),
                'role' => $account['role'],
                'email_verified_at' => now(),
            ]);
        }
    }
}