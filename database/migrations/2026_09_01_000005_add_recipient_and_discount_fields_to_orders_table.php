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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('recipient_name')->nullable()->after('user_id');
            $table->string('recipient_phone', 30)->nullable()->after('recipient_name');
            $table->foreignId('voucher_id')->nullable()->after('payment_status')->constrained('vouchers')->nullOnDelete();
            $table->string('voucher_code', 50)->nullable()->after('voucher_id');
            $table->decimal('subtotal', 12, 2)->nullable()->after('voucher_code');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn([
                'recipient_name',
                'recipient_phone',
                'voucher_id',
                'voucher_code',
                'subtotal',
                'discount_amount',
            ]);
        });
    }
};
