<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Tampilkan kelola pesanan pelanggan (Panel Admin Pesanan).
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.productVariant.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.productVariant.product']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Perbarui status pesanan (pending, diproses, dikirim, selesai).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    Order::STATUS_PENDING,
                    Order::STATUS_DIPROSES,
                    Order::STATUS_DIKIRIM,
                    Order::STATUS_SELESAI,
                ]),
            ],
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        $order->update(['status' => $newStatus]);

        auth()->user()->logActivity(
            'update_order_status',
            "Mengubah status pesanan #{$order->id} dari '{$oldStatus}' menjadi '{$newStatus}'"
        );

        return back()->with('success', "Status pesanan #{$order->id} berhasil diperbarui menjadi '{$newStatus}'.");
    }
}
