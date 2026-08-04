<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Tampilkan daftar produk di wishlist pengguna.
     */
    public function index()
    {
        $wishlists = auth()->user()
            ->wishlists()
            ->with(['product.category', 'product.variants'])
            ->latest('id')
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Tambah/Hapus produk ke/dari wishlist (Toggle).
     */
    public function toggle(Product $product)
    {
        $user = auth()->user();
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Produk berhasil dihapus dari wishlist.';
            $added = false;
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $message = 'Produk berhasil ditambahkan ke wishlist.';
            $added = true;
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'added' => $added,
            ]);
        }

        return back()->with('success', $message);
    }
}
