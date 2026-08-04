@extends('layouts.main')

@section('title', 'Daftar Akun Baru — Sepatu.id')

@section('content')

    <div class="min-h-[75vh] flex items-center justify-center px-4 py-16 bg-white">
        <div class="w-full max-w-md bg-[#f8f8f8] p-8 sm:p-10 rounded-2xl border border-neutral-200 shadow-2xs space-y-6">

            <!-- Title Header -->
            <div class="text-center space-y-1">
                <a href="{{ route('catalog.index') }}" class="font-black text-2xl tracking-tighter uppercase text-neutral-900 inline-block">
                    Sepatu<span class="text-orange-500">.id</span>
                </a>
                <h2 class="text-xl font-black uppercase tracking-tight text-neutral-900 pt-2">
                    Daftar Akun Baru
                </h2>
                <p class="text-xs text-neutral-500 font-medium">
                    Buat akun untuk belanja sepatu dan melacak status pesanan Anda.
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
                    <span>Daftar dengan Google</span>
                </a>

                <!-- Divider -->
                <div class="relative flex items-center justify-center py-2">
                    <div class="border-t border-neutral-300 w-full"></div>
                    <span class="bg-[#f8f8f8] px-3 text-[10px] font-bold text-neutral-400 uppercase tracking-widest absolute">ATAU FORM</span>
                </div>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">
                        Nama Lengkap
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-semibold p-3 bg-white">
                    @if($errors->has('name'))
                        <p class="text-rose-600 text-[11px] font-semibold mt-1">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">
                        Alamat Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-semibold p-3 bg-white">
                    @if($errors->has('email'))
                        <p class="text-rose-600 text-[11px] font-semibold mt-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">
                        Password
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-semibold p-3 bg-white">
                    @if($errors->has('password'))
                        <p class="text-rose-600 text-[11px] font-semibold mt-1">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">
                        Konfirmasi Password
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-semibold p-3 bg-white">
                    @if($errors->has('password_confirmation'))
                        <p class="text-rose-600 text-[11px] font-semibold mt-1">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-xs uppercase tracking-widest transition">
                    Buat Akun Baru
                </button>
            </form>

            <!-- Login Link -->
            <div class="text-center pt-2 border-t border-neutral-200 text-xs text-neutral-600">
                <span>Sudah memiliki akun?</span>
                <a href="{{ route('login') }}" class="font-extrabold text-neutral-900 hover:text-orange-500 uppercase tracking-wider ml-1">
                    Masuk
                </a>
            </div>

        </div>
    </div>

@endsection
