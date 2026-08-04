<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'shipping_address',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    const STATUS_PENDING   = 'pending';
    const STATUS_DIPROSES  = 'diproses';
    const STATUS_DIKIRIM   = 'dikirim';
    const STATUS_SELESAI   = 'selesai';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}