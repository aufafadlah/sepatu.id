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

        // roles bisa dikirim sebagai beberapa argumen, atau satu string
        // dengan koma (misal 'role:super_admin,admin_produk') tergantung cara
        // Laravel mem-parsing parameter middleware, jadi kita pecah dulu
        // untuk jaga-jaga.
        $allowedRoles = collect($roles)
            ->flatMap(fn ($role) => explode(',', $role))
            ->map(fn ($role) => trim($role))
            ->filter()
            ->all();

        if (! in_array($user->role, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}