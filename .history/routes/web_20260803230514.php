->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware role di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();