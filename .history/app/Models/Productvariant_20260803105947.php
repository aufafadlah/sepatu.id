<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'stock',
    ];

    public function product()
    {
        return $this->belongsTo(poduct::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    /**
     * True kalau stok di bawah batas minimum (dipakai buat notifikasi
     * "stok menipis" di dashboard Admin Gudang).
     */
    public function isLowStock(int $threshold = 5): bool
    {
        return $this->stock < $threshold;
    }
}