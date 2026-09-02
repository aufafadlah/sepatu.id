<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'amount',
        'min_spend',
        'max_discount',
        'usage_limit',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Cek apakah voucher masih aktif dan valid untuk nominal subtotal tertentu.
     */
    public function isValidFor(float $subtotal): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Kode voucher ini sedang tidak aktif.'];
        }

        $now = Carbon::now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return ['valid' => false, 'message' => 'Voucher ini baru dapat digunakan mulai ' . $this->starts_at->format('d M Y H:i') . '.'];
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return ['valid' => false, 'message' => 'Kode voucher ini sudah kadaluarsa.'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Kuota penggunaan voucher ini sudah habis.'];
        }

        if ($this->min_spend > 0 && $subtotal < $this->min_spend) {
            return [
                'valid' => false,
                'message' => 'Minimal total belanja untuk voucher ini adalah Rp ' . number_format($this->min_spend, 0, ',', '.') . '.'
            ];
        }

        return ['valid' => true, 'message' => 'Voucher berhasil digunakan!'];
    }

    /**
     * Hitung nilai rupiah potongan diskon.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = ($subtotal * ($this->amount / 100));
            if ($this->max_discount !== null && $this->max_discount > 0) {
                $discount = min($discount, (float)$this->max_discount);
            }
            return min($discount, $subtotal);
        }

        // Fixed rupiah amount
        return min((float)$this->amount, $subtotal);
    }

    /**
     * Format tampilan diskon (cth: "20%" atau "Rp 50.000").
     */
    public function formattedDiscount(): string
    {
        if ($this->type === 'percentage') {
            return (float)$this->amount . '%';
        }
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Status voucher untuk tampilan label.
     */
    public function status(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }
        if ($this->expires_at && Carbon::now()->gt($this->expires_at)) {
            return 'expired';
        }
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return 'exhausted';
        }
        return 'active';
    }
}
