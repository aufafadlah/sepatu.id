<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 150);
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('amount', 12, 2); // Nilai diskon (contoh: 20 utk 20% atau 50000 utk nominal Rp 50.000)
            $table->decimal('min_spend', 12, 2)->default(0); // Minimal total belanja
            $table->decimal('max_discount', 12, 2)->nullable(); // Maksimal diskon dalam rupiah (jika type = percentage)
            $table->unsignedInteger('usage_limit')->nullable(); // Batas kuota klaim penggunaan total
            $table->unsignedInteger('used_count')->default(0); // Berapa kali telah digunakan
            $table->boolean('is_active')->default(true);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
