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
        'discount_percent',
        'discount_price',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'discount_percent' => 'integer',
    ];

    /**
     * Apakah produk memiliki diskon aktif.
     */
    public function hasDiscount(): bool
    {
        return ($this->discount_percent !== null && $this->discount_percent > 0)
            || ($this->discount_price !== null && $this->discount_price > 0 && $this->discount_price < $this->price);
    }

    /**
     * Dapatkan harga final produk setelah diskon (jika ada).
     */
    public function finalPrice(): float
    {
        if ($this->discount_price !== null && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return (float) $this->discount_price;
        }

        if ($this->discount_percent !== null && $this->discount_percent > 0) {
            $cut = ($this->price * $this->discount_percent) / 100;
            return max(0, (float) ($this->price - $cut));
        }

        return (float) $this->price;
    }

    /**
     * Dapatkan persentase diskon terhitung.
     */
    public function discountPercent(): int
    {
        if ($this->discount_percent !== null && $this->discount_percent > 0) {
            return (int) $this->discount_percent;
        }

        if ($this->discount_price !== null && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
        }

        return 0;
    }

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