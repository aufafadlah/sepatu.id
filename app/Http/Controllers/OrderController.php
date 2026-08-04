<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Tampilkan riwayat pesanan milik pelanggan.
     */
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with(['items.productVariant.product'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan pelanggan.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke detail pesanan ini.');
        }

        $order->load(['items.productVariant.product', 'user']);

        return view('orders.show', compact('order'));
    }
}
