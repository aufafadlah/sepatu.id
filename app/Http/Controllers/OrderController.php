<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Tampilkan pesanan aktif & riwayat pesanan milik pelanggan.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->orders()->with(['items.productVariant.product']);

        $currentTab = $request->input('tab', 'all');

        if ($currentTab === 'active') {
            $query->where('status', '!=', Order::STATUS_SELESAI);
        } elseif ($currentTab === 'history') {
            $query->where('status', Order::STATUS_SELESAI);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        $activeCount = auth()->user()->orders()->where('status', '!=', Order::STATUS_SELESAI)->count();
        $historyCount = auth()->user()->orders()->where('status', Order::STATUS_SELESAI)->count();

        return view('orders.index', compact('orders', 'currentTab', 'activeCount', 'historyCount'));
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

    /**
     * Upload bukti pembayaran (transfer bank).
     */
    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status === Order::PAYMENT_PAID) {
            return back()->with('error', 'Pesanan ini sudah dibayar.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Hapus bukti bayar lama jika ada
        if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'payment_status' => Order::PAYMENT_PENDING_VERIFICATION,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }

    /**
     * User konfirmasi barang sudah diterima.
     */
    public function confirmReceived(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status === Order::STATUS_SELESAI) {
            return back()->with('error', 'Pesanan ini sudah dalam status selesai.');
        }

        $order->update(['status' => Order::STATUS_SELESAI]);

        \App\Models\ActivityLog::create([
            'user_id' => $order->user_id,
            'action' => 'konfirmasi_terima_pesanan',
            'description' => "Pelanggan {$order->user->name} mengonfirmasi pesanan #{$order->id} telah diterima (Status pesanan otomatis Selesai).",
        ]);

        return back()->with('success', 'Terima kasih! Pesanan telah berhasil dikonfirmasi diterima. Anda sekarang dapat memberikan bintang dan ulasan produk.');
    }
}
