<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Middleware ini menerima satu atau lebih role yang diizinkan, dipisah
     * koma di route. Contoh pemakaian di routes/web.php:
     *
     *   Route::middleware(['auth', 'role:super_admin'])->group(...);
     *   Route::middleware(['auth', 'role:super_admin,admin_produk'])->group(...);
     *
     * Kalau user belum login, biarkan middleware 'auth' bawaan Laravel yang
     * menangani redirect ke halaman login. Middleware ini hanya cek role
     * SETELAH dipastikan user sudah login.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $allowedRoles = collect($roles)
            ->flatMap(fn ($role) => explode(',', $role))
            ->map(fn ($role) => trim($role))
            ->filter()
            ->all();

        // Jika halaman mewajibkan role 'admin'
        if (in_array('admin', $allowedRoles, true)) {
            if ($user->isAdmin()) {
                return $next($request);
            }
            abort(403, 'Anda tidak memiliki akses ke panel admin.');
        }

        // Jika halaman mewajibkan role 'user' (fitur belanja pelanggan)
        if (in_array('user', $allowedRoles, true)) {
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('warning', 'Akun Administrator tidak dapat melakukan transaksi belanja atau mengakses area pelanggan.');
            }
            if ($user->isCustomer()) {
                return $next($request);
            }
        }

        if (! in_array($user->role, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}