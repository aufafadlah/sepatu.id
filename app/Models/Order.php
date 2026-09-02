<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_name',
        'recipient_phone',
        'status',
        'total',
        'subtotal',
        'discount_amount',
        'voucher_id',
        'voucher_code',
        'shipping_address',
        'notes',
        'payment_method',
        'payment_proof',
        'payment_status',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    const STATUS_PENDING   = 'pending';
    const STATUS_DIPROSES  = 'diproses';
    const STATUS_DIKIRIM   = 'dikirim';
    const STATUS_SELESAI   = 'selesai';

    const PAYMENT_UNPAID              = 'unpaid';
    const PAYMENT_PENDING_VERIFICATION = 'pending_verification';
    const PAYMENT_PAID                = 'paid';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Label status pembayaran yang user-friendly.
     */
    public function paymentStatusLabel(): string
    {
        return match ($this->payment_status) {
            self::PAYMENT_UNPAID => 'Belum Dibayar',
            self::PAYMENT_PENDING_VERIFICATION => 'Menunggu Verifikasi',
            self::PAYMENT_PAID => 'Sudah Dibayar',
            default => 'Belum Dibayar',
        };
    }

    /**
     * Label metode pembayaran yang user-friendly.
     */
    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            'transfer' => 'Transfer Bank',
            'cod' => 'Bayar di Tempat (COD)',
            default => 'Transfer Bank',
        };
    }
}