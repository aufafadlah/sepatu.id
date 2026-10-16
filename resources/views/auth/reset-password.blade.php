@extends('layouts.main')

@section('title', 'Buat Password Baru — Sepatu.id')

@section('content')

    <div class="min-h-[75vh] flex items-center justify-center px-4 py-16 bg-white">
        <div class="w-full max-w-md bg-[#f8f8f8] p-8 sm:p-10 rounded-3xl border border-neutral-200 shadow-sm space-y-6 transition">

            <!-- Title & Brand Header -->
            <div class="text-center space-y-2">
                <a href="{{ route('catalog.index') }}" class="font-black text-2xl tracking-tighter uppercase text-neutral-900 inline-block hover:opacity-90 transition">
                    Sepatu<span class="text-orange-500">.id</span>
                </a>

                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>

                <h2 class="text-xl font-black uppercase tracking-tight text-neutral-900 pt-1">
                    Buat Kata Sandi Baru
                </h2>
                <p class="text-xs text-neutral-500 font-medium">
                    Silakan tentukan kata sandi baru untuk mengamankan akun belanja Anda.
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">
                        Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                           class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-semibold p-3 bg-white">
                    @if($errors->has('email'))
                        <p class="text-rose-600 text-[11px] font-semibold mt-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">
                        Kata Sandi Baru
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           placeholder="Minimal 8 karakter..."
                           class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-semibold p-3 bg-white">
                    @if($errors->has('password'))
                        <p class="text-rose-600 text-[11px] font-semibold mt-1">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">
                        Konfirmasi Kata Sandi Baru
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           placeholder="Ulangi kata sandi baru..."
                           class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-semibold p-3 bg-white">
                    @if($errors->has('password_confirmation'))
                        <p class="text-rose-600 text-[11px] font-semibold mt-1">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-xs uppercase tracking-widest transition shadow-md">
                    Simpan Kata Sandi Baru
                </button>
            </form>

            <div class="text-center pt-2 border-t border-neutral-200 text-xs">
                <a href="{{ route('login') }}" class="font-bold text-neutral-600 hover:text-neutral-900 uppercase tracking-wider transition">
                    &larr; Batalkan &amp; Kembali ke Login
                </a>
            </div>

        </div>
    </div>

@endsection
