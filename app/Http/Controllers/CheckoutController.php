<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout.
     */
    public function index()
    {
        $carts = auth()->user()
            ->carts()
            ->with(['productVariant.product'])
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('catalog.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $totalPrice = $carts->sum(fn ($cart) => $cart->subtotal());

        return view('checkout.index', compact('carts', 'totalPrice'));
    }

    /**
     * Diproses ketika pengguna mengonfirmasi pesanan (Checkout).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string|max:500',
        ]);

        $user = auth()->user();
        $carts = $user->carts()->with(['productVariant.product'])->get();

        if ($carts->isEmpty()) {
            return redirect()->route('catalog.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Cek kecukupan stok seluruh item
        foreach ($carts as $cart) {
            if ($cart->productVariant->stock < $cart->qty) {
                return back()->with('error', "Stok untuk produk {$cart->productVariant->product->name} (Ukuran {$cart->productVariant->size}) tidak mencukupi.");
            }
        }

        $order = DB::transaction(function () use ($user, $carts, $validated) {
            $total = $carts->sum(fn ($cart) => $cart->subtotal());

            $newOrder = Order::create([
                'user_id' => $user->id,
                'status' => Order::STATUS_PENDING,
                'total' => $total,
                'shipping_address' => $validated['shipping_address'],
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_variant_id' => $cart->product_variant_id,
                    'qty' => $cart->qty,
                    'price_at_purchase' => $cart->productVariant->product->price,
                ]);

                // Potong stok produk varian
                $cart->productVariant->decrement('stock', $cart->qty);
            }

            // Kosongkan keranjang
            $user->carts()->delete();

            return $newOrder;
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan Anda berhasil dibuat! Terima kasih telah berbelanja di Sepatu.id.');
    }
}
