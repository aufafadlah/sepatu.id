<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedIds = $request->input('selected');

        if ($selectedIds) {
            if (!is_array($selectedIds)) {
                $selectedIds = explode(',', $selectedIds);
            }
            $selectedIds = array_filter(array_map('intval', (array)$selectedIds));
            if (!empty($selectedIds)) {
                session(['checkout_cart_ids' => $selectedIds]);
            }
        } elseif (!session()->has('checkout_cart_ids')) {
            $allIds = $user->carts()->pluck('id')->toArray();
            session(['checkout_cart_ids' => $allIds]);
        }

        $activeIds = session('checkout_cart_ids', []);
        $carts = $user->carts()
            ->whereIn('id', $activeIds)
            ->with(['productVariant.product'])
            ->get();

        if ($carts->isEmpty()) {
            session()->forget('checkout_cart_ids');
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong atau tidak ada item yang dipilih.');
        }

        $subtotal = (float)$carts->sum(fn ($cart) => $cart->subtotal());
        $appliedVoucher = null;
        $discountAmount = 0;

        // Cek apakah ada voucher yang disimpan di session
        $voucherCode = session('applied_voucher_code');
        if ($voucherCode) {
            $voucher = Voucher::where('code', $voucherCode)->first();
            if ($voucher) {
                $validation = $voucher->isValidFor($subtotal);
                if ($validation['valid']) {
                    $appliedVoucher = $voucher;
                    $discountAmount = $voucher->calculateDiscount($subtotal);
                } else {
                    session()->forget('applied_voucher_code');
                    session()->flash('warning', 'Voucher ' . $voucherCode . ' dibatalkan: ' . $validation['message']);
                }
            } else {
                session()->forget('applied_voucher_code');
            }
        }

        $totalPrice = max(0, $subtotal - $discountAmount);

        return view('checkout.index', compact('carts', 'subtotal', 'discountAmount', 'totalPrice', 'appliedVoucher'));
    }

    /**
     * Terapkan kode voucher promo diskon.
     */
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($request->input('voucher_code')));
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return back()->with('error', 'Kode voucher "' . $code . '" tidak ditemukan.');
        }

        $user = auth()->user();
        $activeIds = session('checkout_cart_ids', []);
        $carts = $user->carts()
            ->when(!empty($activeIds), fn($q) => $q->whereIn('id', $activeIds))
            ->with(['productVariant.product'])
            ->get();
        if ($carts->isEmpty()) {
            return back()->with('error', 'Keranjang belanja Anda kosong.');
        }

        $subtotal = (float)$carts->sum(fn ($cart) => $cart->subtotal());
        $validation = $voucher->isValidFor($subtotal);

        if (!$validation['valid']) {
            return back()->with('error', $validation['message']);
        }

        session(['applied_voucher_code' => $voucher->code]);
        $discount = $voucher->calculateDiscount($subtotal);

        return back()->with('success', 'Kode voucher ' . $voucher->code . ' berhasil diterapkan! Anda hemat Rp ' . number_format($discount, 0, ',', '.') . '.');
    }

    /**
     * Batalkan/hapus voucher yang sedang digunakan.
     */
    public function removeVoucher()
    {
        session()->forget('applied_voucher_code');
        return back()->with('info', 'Kode voucher berhasil dihapus dari pesanan.');
    }

    /**
     * Diproses ketika pengguna mengonfirmasi pesanan (Checkout).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name'  => 'required|string|max:100',
            'recipient_phone' => ['required', 'regex:/^[0-9]{12,13}$/'],
            'shipping_address' => 'required|string|max:500',
            'payment_method'  => 'required|in:transfer,cod',
            'notes'           => 'nullable|string|max:1000',
            'voucher_code'    => 'nullable|string|max:50',
        ], [
            'recipient_phone.regex' => 'Nomor WhatsApp/Telepon harus berupa angka dan terdiri dari 12–13 digit.',
        ]);

        $user = auth()->user();
        $selectedIds = $request->input('selected', session('checkout_cart_ids'));
        if ($selectedIds && !is_array($selectedIds)) {
            $selectedIds = explode(',', $selectedIds);
        }

        $cartsQuery = $user->carts()->with(['productVariant.product']);
        if ($selectedIds && is_array($selectedIds) && count($selectedIds) > 0) {
            $cartsQuery->whereIn('id', array_map('intval', $selectedIds));
        }
        $carts = $cartsQuery->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong atau belum ada item yang dipilih.');
        }

        // Cek kecukupan stok seluruh item
        foreach ($carts as $cart) {
            if ($cart->productVariant->stock < $cart->qty) {
                return back()->with('error', "Stok untuk produk {$cart->productVariant->product->name} (Ukuran {$cart->productVariant->size}) tidak mencukupi.");
            }
        }

        $subtotal = (float)$carts->sum(fn ($cart) => $cart->subtotal());
        $voucher = null;
        $discountAmount = 0;

        // Cek voucher dari request atau session
        $codeToUse = $validated['voucher_code'] ?? session('applied_voucher_code');
        if ($codeToUse) {
            $codeToUse = strtoupper(trim($codeToUse));
            $foundVoucher = Voucher::where('code', $codeToUse)->first();
            if ($foundVoucher) {
                $validation = $foundVoucher->isValidFor($subtotal);
                if ($validation['valid']) {
                    $voucher = $foundVoucher;
                    $discountAmount = $voucher->calculateDiscount($subtotal);
                } else {
                    return back()->with('error', 'Voucher ' . $codeToUse . ': ' . $validation['message']);
                }
            }
        }

        $finalTotal = max(0, $subtotal - $discountAmount);

        $order = DB::transaction(function () use ($user, $carts, $validated, $subtotal, $discountAmount, $finalTotal, $voucher) {
            $paymentStatus = $validated['payment_method'] === 'cod'
                ? Order::PAYMENT_PAID
                : Order::PAYMENT_UNPAID;

            $newOrder = Order::create([
                'user_id' => $user->id,
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'voucher_id' => $voucher?->id,
                'voucher_code' => $voucher?->code,
                'total' => $finalTotal,
                'shipping_address' => $validated['shipping_address'],
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
            ]);

            // Jika ada voucher, increment pemakaiannya
            if ($voucher) {
                $voucher->increment('used_count');
            }

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id'           => $newOrder->id,
                    'product_variant_id' => $cart->product_variant_id,
                    'qty'                => $cart->qty,
                    // Gunakan harga setelah diskon produk jika ada
                    'price_at_purchase'  => $cart->productVariant->product->finalPrice(),
                ]);

                // Potong stok produk varian
                $cart->productVariant->decrement('stock', $cart->qty);
            }

            // HANYA hapus item yang di-checkout! Item yang tidak dipilih tetap tersimpan di keranjang
            $user->carts()->whereIn('id', $carts->pluck('id'))->delete();

            // Bersihkan session voucher dan checkout
            session()->forget(['applied_voucher_code', 'checkout_cart_ids']);

            return $newOrder;
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan Anda berhasil dibuat! Terima kasih telah berbelanja di Sepatu.id.');
    }
}
