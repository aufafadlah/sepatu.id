@extends('layouts.admin')

@section('title', 'Tambah Voucher — Admin Sepatu.id')
@section('page_title', 'Tambah Voucher Diskon')

@section('content')

    <div class="max-w-2xl mx-auto space-y-6" x-data="{ discountType: '{{ old('type', 'percentage') }}' }">

        <a href="{{ route('admin.vouchers.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Voucher</span>
        </a>

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center gap-3.5 border-b border-slate-100 pb-5 mb-6">
                <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Buat Kode Voucher Baru</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Tentukan kode kupon, tipe diskon persen atau nominal rupiah, serta masa berlakunya.</p>
                </div>
            </div>

            <form action="{{ route('admin.vouchers.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kode Voucher -->
                    <div>
                        <label for="code" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kode Voucher <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" required
                               placeholder="Contoh: HEMAT20, GAJIAN50"
                               class="w-full uppercase rounded-xl border border-slate-200 text-xs font-mono font-bold p-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 transition">
                        @error('code')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama / Label Promo -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Promo <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Diskon Pelanggan Baru 20%"
                               class="w-full rounded-xl border border-slate-200 text-xs font-medium p-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 transition">
                        @error('name')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tipe Diskon -->
                    <div>
                        <label for="type" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tipe Potongan Diskon <span class="text-rose-500">*</span>
                        </label>
                        <select id="type" name="type" x-model="discountType" required
                                class="w-full rounded-xl border border-slate-200 text-xs font-semibold p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rupiah Rp)</option>
                        </select>
                        @error('type')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nilai Diskon -->
                    <div>
                        <label for="amount" class="block text-xs font-bold text-slate-700 mb-1.5">
                            <span x-show="discountType === 'percentage'">Besar Persen Diskon (%) <span class="text-rose-500">*</span></span>
                            <span x-show="discountType === 'fixed'">Nominal Potongan (Rp) <span class="text-rose-500">*</span></span>
                        </label>
                        <input type="number" step="0.01" min="0.01" id="amount" name="amount" value="{{ old('amount') }}" required
                               :placeholder="discountType === 'percentage' ? 'Contoh: 15 (artinya 15%)' : 'Contoh: 50000 (artinya Rp 50.000)'"
                               class="w-full rounded-xl border border-slate-200 text-xs font-bold p-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 transition">
                        @error('amount')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Minimal Belanja -->
                    <div>
                        <label for="min_spend" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Minimal Belanja (Rp) <span class="text-slate-400 font-normal">(0 jika tanpa syarat)</span>
                        </label>
                        <input type="number" step="1000" min="0" id="min_spend" name="min_spend" value="{{ old('min_spend', 0) }}"
                               placeholder="0"
                               class="w-full rounded-xl border border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 transition">
                        @error('min_spend')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Maksimal Diskon (khusus persentase) -->
                    <div x-show="discountType === 'percentage'">
                        <label for="max_discount" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Maksimal Potongan (Rp) <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="number" step="1000" min="0" id="max_discount" name="max_discount" value="{{ old('max_discount') }}"
                               placeholder="Contoh: 100000 (maksimal Rp 100rb)"
                               class="w-full rounded-xl border border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 transition">
                        @error('max_discount')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kuota Penggunaan -->
                    <div :class="discountType === 'percentage' ? 'sm:col-span-2' : ''">
                        <label for="usage_limit" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Batas Kuota Penggunaan Total <span class="text-slate-400 font-normal">(Kosongkan jika tak terbatas)</span>
                        </label>
                        <input type="number" min="1" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}"
                               placeholder="Contoh: 100 (bisa dipakai 100x)"
                               class="w-full rounded-xl border border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 transition">
                        @error('usage_limit')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Periode Masa Berlaku -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="starts_at" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Mulai Berlaku Pada <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}"
                               class="w-full rounded-xl border border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        @error('starts_at')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expires_at" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kadaluarsa Pada <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at') }}"
                               class="w-full rounded-xl border border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        @error('expires_at')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status Aktif Toggle -->
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <span class="text-xs font-bold text-slate-800">Aktifkan voucher ini segera setelah disimpan</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan &amp; Publikasikan Voucher</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

@endsection
