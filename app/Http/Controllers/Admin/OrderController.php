<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Tampilkan kelola pesanan pelanggan (Panel Admin Pesanan).
     */
    public function index(Request $request)
    {
        // Jalankan auto-cancel pesanan transfer yang melewati 5 hari otomatis
        Order::cancelExpiredOrders();

        $query = Order::with(['user', 'items.productVariant.product']);

        $currentTab = $request->input('tab', 'all');

        if ($currentTab === 'unviewed') {
            $query->where('is_viewed_by_admin', false);
        } elseif ($currentTab === 'unprinted') {
            $query->where('is_printed', false);
        } elseif ($currentTab === 'active') {
            $query->whereNotIn('status', [Order::STATUS_SELESAI, Order::STATUS_DIBATALKAN]);
        } elseif ($currentTab === 'history') {
            $query->where('status', Order::STATUS_SELESAI);
        } elseif ($currentTab === 'cancelled') {
            $query->where('status', Order::STATUS_DIBATALKAN);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $cleanId = ltrim($search, '#');

            $query->where(function ($q) use ($search, $cleanId) {
                $q->where('id', 'like', "%{$cleanId}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('recipient_phone', 'like', "%{$search}%")
                  ->orWhere('shipping_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        // Hitung statistik untuk badge status
        $unviewedCount = Order::where('is_viewed_by_admin', false)->count();
        $unprintedCount = Order::where('is_printed', false)->count();
        $pendingVerificationCount = Order::where('payment_status', Order::PAYMENT_PENDING_VERIFICATION)->count();
        $activeCount = Order::whereNotIn('status', [Order::STATUS_SELESAI, Order::STATUS_DIBATALKAN])->count();
        $historyCount = Order::where('status', Order::STATUS_SELESAI)->count();
        $cancelledCount = Order::where('status', Order::STATUS_DIBATALKAN)->count();

        return view('admin.orders.index', compact(
            'orders',
            'currentTab',
            'unviewedCount',
            'unprintedCount',
            'pendingVerificationCount',
            'activeCount',
            'historyCount',
            'cancelledCount'
        ));
    }

    /**
     * Endpoint Polling Real-time untuk deteksi pesanan baru.
     */
    public function checkNewOrders(Request $request)
    {
        $unviewedCount = Order::where('is_viewed_by_admin', false)->count();
        $latestOrder = Order::with('user')
            ->where('is_viewed_by_admin', false)
            ->latest('id')
            ->first();

        return response()->json([
            'unviewed_count' => $unviewedCount,
            'has_new' => $unviewedCount > 0,
            'latest' => $latestOrder ? [
                'id' => $latestOrder->id,
                'customer' => $latestOrder->recipient_name ?? $latestOrder->user->name ?? 'Pelanggan',
                'total' => 'Rp ' . number_format($latestOrder->total, 0, ',', '.'),
                'created_at' => $latestOrder->created_at->diffForHumans(),
            ] : null,
        ]);
    }

    /**
     * Tampilkan detail pesanan beserta bukti pembayaran untuk diverifikasi admin.
     */
    public function show(Order $order)
    {
        // Tandai pesanan sudah dilihat oleh admin
        if (! $order->is_viewed_by_admin) {
            $order->update(['is_viewed_by_admin' => true]);
        }

        $order->load(['user', 'items.productVariant.product']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cetak Label Alamat Pengiriman Pesanan (dan tandai sudah dicetak).
     */
    public function printAddress(Order $order)
    {
        $order->load(['user', 'items.productVariant.product']);

        // Tandai struk/label sudah dicetak
        if (! $order->is_printed) {
            $order->update([
                'is_printed' => true,
                'printed_at' => now(),
            ]);

            auth()->user()->logActivity(
                'print_order_receipt',
                "Mencetak struk label pengiriman untuk pesanan #{$order->id}"
            );
        }

        return view('admin.orders.print_address', compact('order'));
    }

    /**
     * Perbarui status pesanan (pending, diproses, dikirim, selesai, dibatalkan).
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
                    Order::STATUS_DIBATALKAN,
                ]),
            ],
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($order, $newStatus, $oldStatus) {
            // Jika dibatalkan dan sebelumnya bukan dibatalkan, kembalikan stok
            if ($newStatus === Order::STATUS_DIBATALKAN && $oldStatus !== Order::STATUS_DIBATALKAN) {
                foreach ($order->items as $item) {
                    if ($item->productVariant) {
                        $item->productVariant->increment('stock', $item->qty);
                    }
                }
                if ($order->voucher) {
                    $order->voucher->decrement('used_count');
                }
            }

            $order->update(['status' => $newStatus]);

            auth()->user()->logActivity(
                'update_order_status',
                "Mengubah status pesanan #{$order->id} dari '{$oldStatus}' menjadi '{$newStatus}'"
            );
        });

        return back()->with('success', "Status pesanan #{$order->id} berhasil diperbarui menjadi '{$order->statusLabel()}'.");
    }

    /**
     * Batalkan pesanan oleh Admin secara manual beserta pengembalian stok.
     */
    public function cancelOrder(Request $request, Order $order)
    {
        if ($order->status === Order::STATUS_DIBATALKAN) {
            return back()->with('warning', 'Pesanan ini sudah dalam status dibatalkan.');
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'status' => Order::STATUS_DIBATALKAN,
            ]);

            // Kembalikan stok
            foreach ($order->items as $item) {
                if ($item->productVariant) {
                    $item->productVariant->increment('stock', $item->qty);
                }
            }

            if ($order->voucher) {
                $order->voucher->decrement('used_count');
            }

            auth()->user()->logActivity(
                'cancel_order_manual',
                "Admin membatalkan pesanan #{$order->id}. Stok dikembalikan ke inventaris gudang."
            );
        });

        return back()->with('success', "Pesanan #{$order->id} berhasil dibatalkan dan stok dikembalikan.");
    }

    /**
     * Trigger manual pembatalan pesanan kedaluwarsa (auto-cancel 5 hari).
     */
    public function runAutoCancel(Request $request)
    {
        $count = Order::cancelExpiredOrders();

        return back()->with('success', "Pemeriksaan selesai. Sebanyak {$count} pesanan kedaluwarsa berhasil dibatalkan secara otomatis.");
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

        return back()->with('success', "Status verifikasi pembayaran pesanan #{$order->id} diperbarui menjadi '{$order->paymentStatusLabel()}'.");
    }
}
