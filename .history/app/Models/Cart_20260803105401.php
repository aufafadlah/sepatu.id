<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_variant_id',
        'qty',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Akses cepat ke produk induk lewat varian.
     */
    public function product()
    {
        return $this->productVariant->product ?? null;
    }

    public function subtotal()
    {
        return $this->qty * ($this->productVariant->product->price ?? 0);
    }
}