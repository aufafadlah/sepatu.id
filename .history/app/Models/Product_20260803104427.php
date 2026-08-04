<?php

namespace App\Models;
App/models/Product
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ---------- Relasi ----------

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // ---------- Helper Role ----------

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminProduk(): bool
    {
        return $this->role === 'admin_produk';
    }

    public function isAdminGudang(): bool
    {
        return $this->role === 'admin_gudang';
    }

    public function isAdminPesanan(): bool
    {
        return $this->role === 'admin_pesanan';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'user';
    }

    /**
     * True kalau role termasuk salah satu jenis admin (bukan pelanggan).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'admin_produk',
            'admin_gudang',
            'admin_pesanan',
        ]);
    }

    /**
     * Catat aktivitas admin ke tabel activity_logs.
     * Panggil dari controller, contoh:
     * auth()->user()->logActivity('update_stock', 'Update stok produk X ukuran 42 menjadi 20');
     */
    public function logActivity(string $action, ?string $description = null): void
    {
        $this->activityLogs()->create([
            'action' => $action,
            'description' => $description,
        ]);
    }
}