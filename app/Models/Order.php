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
        'is_viewed_by_admin',
        'is_printed',
        'printed_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'is_viewed_by_admin' => 'boolean',
        'is_printed' => 'boolean',
        'printed_at' => 'datetime',
    ];

    const STATUS_PENDING     = 'pending';
    const STATUS_DIPROSES    = 'diproses';
    const STATUS_DIKIRIM     = 'dikirim';
    const STATUS_SELESAI     = 'selesai';
    const STATUS_DIBATALKAN  = 'dibatalkan';

    const PAYMENT_UNPAID              = 'unpaid';
    const PAYMENT_PENDING_VERIFICATION = 'pending_verification';
    const PAYMENT_PAID                = 'paid';

    /**
     * Waktu kedaluwarsa pesanan jika belum transfer (5 hari sejak dibuat).
     */
    public function expiresAt(): \Carbon\Carbon
    {
        return $this->created_at->copy()->addDays(5);
    }

    /**
     * Cek apakah pesanan telah melewati batas waktu 5 hari.
     */
    public function isExpiredForAutoCancel(): bool
    {
        if ($this->payment_method !== 'transfer') {
            return false;
        }

        if ($this->payment_status === self::PAYMENT_PAID || $this->status === self::STATUS_SELESAI || $this->status === self::STATUS_DIBATALKAN) {
            return false;
        }

        if (!empty($this->payment_proof)) {
            return false;
        }

        return $this->created_at->lt(now()->subDays(5));
    }

    /**
     * Batalkan seluruh pesanan kedaluwarsa (otomatis auto-cancel setelah 5 hari).
     */
    public static function cancelExpiredOrders(): int
    {
        $expiredOrders = self::where('payment_method', 'transfer')
            ->where('status', self::STATUS_PENDING)
            ->whereNull('payment_proof')
            ->where('created_at', '<=', now()->subDays(5))
            ->with(['items.productVariant', 'voucher'])
            ->get();

        $count = 0;
        foreach ($expiredOrders as $order) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                $order->update([
                    'status' => self::STATUS_DIBATALKAN,
                ]);

                // Kembalikan stok produk varian
                foreach ($order->items as $item) {
                    if ($item->productVariant) {
                        $item->productVariant->increment('stock', $item->qty);
                    }
                }

                // Kembalikan kuota pemakaian voucher jika ada
                if ($order->voucher) {
                    $order->voucher->decrement('used_count');
                }

                // Catat log audit pembatalan
                \App\Models\ActivityLog::create([
                    'user_id' => $order->user_id,
                    'action' => 'auto_cancel_order',
                    'description' => "Pesanan #{$order->id} otomatis dibatalkan oleh sistem karena melewati batas 5 hari tanpa bukti pembayaran. Stok dikembalikan.",
                ]);
            });
            $count++;
        }

        return $count;
    }

    /**
     * Label status pesanan yang user-friendly.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_DIPROSES => 'Sedang Diproses',
            self::STATUS_DIKIRIM => 'Sedang Dikirim',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

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