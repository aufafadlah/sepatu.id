@extends('layouts.main')

@section('title', 'Lupa Password — Sepatu.id')

@section('content')

    <div class="min-h-[75vh] flex items-center justify-center px-4 py-16 bg-white">
        <div class="w-full max-w-md bg-[#f8f8f8] p-8 sm:p-10 rounded-3xl border border-neutral-200 shadow-sm space-y-6 transition">

            <!-- Title & Brand Header -->
            <div class="text-center space-y-2">
                <a href="{{ route('catalog.index') }}" class="font-black text-2xl tracking-tighter uppercase text-neutral-900 inline-block hover:opacity-90 transition">
                    Sepatu<span class="text-orange-500">.id</span>
                </a>
                
                <div class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-200 text-orange-600 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>

                <h2 class="text-xl font-black uppercase tracking-tight text-neutral-900 pt-1">
                    Atur Ulang Kata Sandi
                </h2>
                <p class="text-xs text-neutral-500 font-medium leading-relaxed px-2">
                    Masukkan alamat email akun Anda. Kami akan mengirimkan tautan aman untuk membuat password baru.
                </p>
            </div>

            <!-- Session Status Alert (Link Sent) -->
            @if (session('status'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-start gap-3 shadow-2xs">
                    <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <p class="font-bold">{{ session('status') }}</p>
                        <p class="text-[11px] text-emerald-700 font-normal">
                            Silakan periksa kotak masuk atau folder spam email Anda.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1.5">
                        Alamat Email Akun
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                               placeholder="nama@email.com"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border-neutral-300 focus:ring-2 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-semibold bg-white transition">
                    </div>
                    @if($errors->has('email'))
                        <p class="text-rose-600 text-[11px] font-semibold mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ $errors->first('email') }}</span>
                        </p>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-xs uppercase tracking-widest transition shadow-md flex items-center justify-center gap-2 group">
                    <span>Kirim Tautan Reset</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Navigation Back to Login -->
            <div class="text-center pt-2 border-t border-neutral-200 text-xs">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 font-bold text-neutral-600 hover:text-neutral-900 uppercase tracking-wider transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Halaman Masuk</span>
                </a>
            </div>

        </div>
    </div>

@endsection
