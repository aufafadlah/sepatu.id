@extends('layouts.admin')

@section('title', 'Tambah Kategori — Admin Sepatu.id')
@section('page_title', 'Tambah Kategori')

@section('content')

    <div class="max-w-xl space-y-6">

        <a href="{{ route('admin.produk.kategori.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Kategori</span>
        </a>

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center gap-3.5 border-b border-slate-100 pb-5 mb-6">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Tambah Kategori Baru</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Kategori digunakan untuk mengelompokkan koleksi sepatu di katalog.</p>
                </div>
            </div>

            <form action="{{ route('admin.produk.kategori.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h7M13 11h7M13 15h7" />
                            </svg>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Running Shoes, Sneakers, Formal..." 
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 transition">
                    </div>
                    @error('name')
                        <p class="text-rose-500 text-[11px] font-bold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan Kategori</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

@endsection
