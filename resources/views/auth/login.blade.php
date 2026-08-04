@extends('layouts.main')

@section('title', 'Masuk Akun — Sepatu.id')

@section('content')

    <div class="min-h-[75vh] flex items-center justify-center px-4 py-16 bg-white">
        <div class="w-full max-w-md bg-[#f8f8f8] p-8 sm:p-10 rounded-2xl border border-neutral-200 shadow-2xs space-y-6">

            <!-- Title Header -->
            <div class="text-center space-y-1">
                <a href="{{ route('catalog.index') }}" class="font-black text-2xl tracking-tighter uppercase text-neutral-900 inline-block">
                    Sepatu<span class="text-orange-500">.id</span>
                </a>
                <h2 class="text-xl font-black uppercase tracking-tight text-neutral-900 pt-2">
                    Masuk Akun
                </h2>
                <p class="text-xs text-neutral-500 font-medium">
                    Silakan masuk untuk melanjutkan transaksi dan mengelola pesanan.
                </p>
            </div>

            <!-- Google OAuth Button -->
            <div class="space-y-3">
                <a href="{{ route('auth.google') }}"
                   class="w-full py-3 px-4 rounded-full bg-white hover:bg-neutral-100 border border-neutral-300 text-neutral-800 text-xs font-extrabold uppercase tracking-wider flex items-center justify-center gap-3 transition shadow-2xs">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.24v3.15C3.26 21.37 7.36 24 12 24z"/>
                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.24C.45 8.16 0 9.99 0 12s.45 3.84 1.24 5.42l4.04-3.15z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.36 0 3.26 2.63 1.24 6.58l4.04 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                    </svg>
                    <span>Masuk dengan Google</span>
                </a>

                <!-- Divider -->
                <div class="relative flex items-center justify-center py-2">
                    <div class="border-t border-neutral-300 w-full"></div>
                    <span class="bg-[#f8f8f8] px-3 text-[10px] font-bold text-neutral-400 uppercase tracking-widest absolute">ATAU EMAIL</span>
                </div>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">
                        Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-semibold p-3 bg-white">
                    @if($errors->has('email'))
                        <p class="text-rose-600 text-[11px] font-semibold mt-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900">
                            Password
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-neutral-500 hover:text-neutral-900">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-semibold p-3 bg-white">
                    @if($errors->has('password'))
                        <p class="text-rose-600 text-[11px] font-semibold mt-1">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-neutral-300 text-neutral-900 focus:ring-neutral-900">
                    <label for="remember_me" class="ms-2 text-xs font-medium text-neutral-600">
                        Ingat Saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-xs uppercase tracking-widest transition">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Register Link -->
            <div class="text-center pt-2 border-t border-neutral-200 text-xs text-neutral-600">
                <span>Belum memiliki akun?</span>
                <a href="{{ route('register') }}" class="font-extrabold text-neutral-900 hover:text-orange-500 uppercase tracking-wider ml-1">
                    Daftar Baru
                </a>
            </div>

        </div>
    </div>

@endsection
