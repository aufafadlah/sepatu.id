<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function totalStock()
    {
        return $this->variants()->sum('stock');
    }

    /**
     * True kalau semua varian ukuran stoknya habis (dipakai buat badge
     * "Stok Habis" di card katalog).
     */
    public function isOutOfStock(): bool
    {
        return $this->totalStock() <= 0;
    }

    /**
     * True kalau produk dibuat kurang dari 7 hari lalu (dipakai buat badge "Baru").
     */
    public function isNew(): bool
    {
        return $this->created_at->gt(now()->subDays(7));
    }
}