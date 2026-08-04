<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang belanja pengguna.
     */
    public function index()
    {
        $carts = auth()->user()
            ->carts()
            ->with(['productVariant.product.category'])
            ->latest()
            ->get();

        $totalPrice = $carts->sum(fn ($cart) => $cart->subtotal());

        return view('cart.index', compact('carts', 'totalPrice'));
    }

    /**
     * Tambahkan varian produk ke keranjang belanja.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'qty' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($validated['product_variant_id']);

        if ($variant->stock < $validated['qty']) {
            return back()->with('error', "Stok ukuran {$variant->size} tersisa {$variant->stock} pasang.");
        }

        $user = auth()->user();
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($cartItem) {
            $newQty = $cartItem->qty + $validated['qty'];
            if ($newQty > $variant->stock) {
                return back()->with('error', "Stok tidak mencukupi untuk menambah item ke keranjang (stok tersedia: {$variant->stock}).");
            }
            $cartItem->update(['qty' => $newQty]);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_variant_id' => $variant->id,
                'qty' => $validated['qty'],
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Perbarui jumlah item di keranjang.
     */
    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        if ($cart->productVariant->stock < $validated['qty']) {
            return back()->with('error', "Stok ukuran {$cart->productVariant->size} tidak mencukupi (tersisa {$cart->productVariant->stock}).");
        }

        $cart->update(['qty' => $validated['qty']]);

        return back()->with('success', 'Jumlah produk di keranjang diperbarui.');
    }

    /**
     * Hapus item dari keranjang.
     */
    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
