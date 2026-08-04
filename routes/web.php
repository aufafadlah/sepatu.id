<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

// =========================================================
// PUBLIK (tidak perlu login)
// =========================================================
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produk/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// =========================================================
// BUTUH LOGIN (semua role, termasuk 'user' pelanggan)
// =========================================================
Route::middleware(['auth'])->group(function () {

    // Redirection Dashboard pintar berdasarkan Role
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('catalog.index');
    })->name('dashboard');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Pesanan Saya
    Route::get('/pesanan-saya', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan-saya/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Review Produk
    Route::post('/produk/{product}/review', [ReviewController::class, 'store'])->name('review.store');
});

// =========================================================
// ADMIN — dibatasi middleware 'role' sesuai kebutuhan tiap panel
// =========================================================
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/users', AdminUserController::class)->except(['show']);
        Route::get('/laporan/export', [DashboardController::class, 'exportPdf'])->name('laporan.export');
    });

Route::middleware(['auth', 'role:super_admin,admin_produk'])
    ->prefix('admin/produk')
    ->name('admin.produk.')
    ->group(function () {
        Route::resource('/', AdminProductController::class)->parameters(['' => 'product']);
        Route::resource('/kategori', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    });

Route::middleware(['auth', 'role:super_admin,admin_gudang'])
    ->prefix('admin/gudang')
    ->name('admin.gudang.')
    ->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::patch('/{variant}', [StockController::class, 'update'])->name('update');
    });

Route::middleware(['auth', 'role:super_admin,admin_pesanan'])
    ->prefix('admin/pesanan')
    ->name('admin.pesanan.')
    ->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('update-status');
    });

require __DIR__.'/auth.php';