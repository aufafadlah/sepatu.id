<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general');
            $table->string('label', 200)->nullable();
            $table->timestamps();
        });

        // Seed default settings
        \DB::table('site_settings')->insert([
            [
                'key' => 'hero_badge',
                'value' => 'New Concept Collection 2026',
                'group' => 'hero',
                'label' => 'Badge Text (di atas judul hero)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'hero_title',
                'value' => 'sepatu.id',
                'group' => 'hero',
                'label' => 'Judul Utama Hero',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Koleksi Sepatu Sneaker & Streetwear Lokal dengan siluet modern, bantalan empuk, dan kenyamanan aktivitas harian.',
                'group' => 'hero',
                'label' => 'Deskripsi Hero (di bawah judul)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'announcement_bar',
                'value' => 'Gratis Ongkir Seluruh Indonesia Pembelian > Rp 500.000',
                'group' => 'general',
                'label' => 'Teks Announcement Bar (banner atas)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'announcement_left',
                'value' => 'Garansi 100% Original & Bebas Tukar Ukuran',
                'group' => 'general',
                'label' => 'Teks Announcement Kiri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'announcement_right',
                'value' => 'CS WhatsApp: 0812-3456-789',
                'group' => 'general',
                'label' => 'Teks Announcement Kanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_1_title',
                'value' => '100% Original Quality',
                'group' => 'features',
                'label' => 'Fitur #1 Judul',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_1_desc',
                'value' => 'Pilihan material sepatu kelas atas dengan durabilitas tinggi.',
                'group' => 'features',
                'label' => 'Fitur #1 Deskripsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_2_title',
                'value' => 'Garansi Tukar Ukuran',
                'group' => 'features',
                'label' => 'Fitur #2 Judul',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_2_desc',
                'value' => 'Penukaran varian ukuran tanpa ribet hingga 7 hari.',
                'group' => 'features',
                'label' => 'Fitur #2 Deskripsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_3_title',
                'value' => 'Ulasan Terverifikasi',
                'group' => 'features',
                'label' => 'Fitur #3 Judul',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_3_desc',
                'value' => 'Setiap ulasan berasal dari pembeli nyata yang menyelesaikan pesanan.',
                'group' => 'features',
                'label' => 'Fitur #3 Deskripsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_4_title',
                'value' => 'Pengiriman Cepat',
                'group' => 'features',
                'label' => 'Fitur #4 Judul',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_4_desc',
                'value' => 'Pengiriman aman & efisien ke seluruh kota di Indonesia.',
                'group' => 'features',
                'label' => 'Fitur #4 Deskripsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
