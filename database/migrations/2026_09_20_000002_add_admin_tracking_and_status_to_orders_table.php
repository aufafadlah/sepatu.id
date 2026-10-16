<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum status menjadi string fleksibel agar mendukung 'dibatalkan'
        try {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(30) NOT NULL DEFAULT 'pending'");
        } catch (\Throwable $e) {
            // Fallback jika bukan MySQL
            Schema::table('orders', function (Blueprint $table) {
                $table->string('status', 30)->default('pending')->change();
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_viewed_by_admin')->default(false)->after('payment_status');
            $table->boolean('is_printed')->default(false)->after('is_viewed_by_admin');
            $table->timestamp('printed_at')->nullable()->after('is_printed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_viewed_by_admin', 'is_printed', 'printed_at']);
        });
    }
};
