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

        $currentTab = $request->input('tab', 'all');

        if ($currentTab === 'active') {
            $query->where('status', '!=', Order::STATUS_SELESAI);
        } elseif ($currentTab === 'history') {
            $query->where('status', Order::STATUS_SELESAI);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan beserta bukti pembayaran untuk diverifikasi admin.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.productVariant.product']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cetak Label Alamat Pengiriman Pesanan.
     */
    public function printAddress(Order $order)
    {
        $order->load(['user', 'items.productVariant.product']);

        return view('admin.orders.print_address', compact('order'));
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

    /**
     * Verifikasi status pembayaran pelanggan (unpaid, pending_verification, paid).
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => [
                'required',
                Rule::in([
                    Order::PAYMENT_UNPAID,
                    Order::PAYMENT_PENDING_VERIFICATION,
                    Order::PAYMENT_PAID,
                ]),
            ],
        ]);

        $oldStatus = $order->payment_status;
        $newStatus = $validated['payment_status'];

        $order->update(['payment_status' => $newStatus]);

        auth()->user()->logActivity(
            'update_payment_status',
            "Mengubah status pembayaran pesanan #{$order->id} dari '{$oldStatus}' menjadi '{$newStatus}'"
        );

        return back()->with('success', "Status pembayaran pesanan #{$order->id} berhasil diperbarui menjadi '{$order->paymentStatusLabel()}'.");
    }
}
