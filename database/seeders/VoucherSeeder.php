<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Voucher::firstOrCreate(
            ['code' => 'SEPATUBARU20'],
            [
                'name' => 'Diskon Pengguna Baru 20%',
                'type' => 'percentage',
                'amount' => 20,
                'min_spend' => 100000,
                'max_discount' => 50000,
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(6),
            ]
        );

        Voucher::firstOrCreate(
            ['code' => 'HEMAT50RB'],
            [
                'name' => 'Potongan Langsung Rp 50.000',
                'type' => 'fixed',
                'amount' => 50000,
                'min_spend' => 250000,
                'max_discount' => null,
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(3),
            ]
        );
    }
}
