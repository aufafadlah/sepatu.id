@extends('layouts.admin')

@section('title', 'Voucher & Diskon — Admin Sepatu.id')
@section('page_title', 'Kelola Voucher & Diskon')

@section('content')

    <div class="space-y-6">

        <!-- Top Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div class="flex items-center gap-4">
               
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Voucher Promo &amp; Diskon</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola kupon potongan harga (persen atau nominal tetap) yang dapat digunakan pembeli saat checkout.</p>
                </div>
            </div>
            <a href="{{ route('admin.vouchers.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Voucher Baru</span>
            </a>
        </div>

        <!-- Filter Controls -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('admin.vouchers.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama voucher..."
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                </div>

                <select name="status" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition">
                    Filter
                </button>

                @if(request('search') || request('status'))
                    <a href="{{ route('admin.vouchers.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        <span>Reset</span>
                    </a>
                @endif
            </form>

            <div class="flex items-center gap-2 self-start sm:self-center">
                <span class="text-xs font-semibold text-slate-500">
                    Total: <strong class="text-slate-900">{{ $totalVouchers }}</strong> voucher
                </span>
            </div>
        </div>

        <!-- Voucher Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 uppercase font-semibold tracking-wider text-[10px]">
                        <tr>
                            <th class="p-4 w-12 text-center">No.</th>
                            <th class="p-4">Kode Voucher</th>
                            <th class="p-4">Nama Promo</th>
                            <th class="p-4">Nilai Diskon</th>
                            <th class="p-4">Min. Belanja</th>
                            <th class="p-4">Penggunaan</th>
                            <th class="p-4">Masa Berlaku</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse($vouchers as $voucher)
                            @php
                                $status = $voucher->status();
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 text-center font-bold text-slate-400">
                                    {{ ($vouchers->currentPage() - 1) * $vouchers->perPage() + $loop->iteration }}
                                </td>
                                <td class="p-4 font-bold">
                                    <span class="px-2.5 py-1 rounded-lg bg-orange-50 text-orange-700 border border-orange-200/80 font-mono text-xs font-extrabold uppercase">
                                        {{ $voucher->code }}
                                    </span>
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    {{ $voucher->name }}
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    @if($voucher->type === 'percentage')
                                        <span class="text-indigo-600">{{ (float)$voucher->amount }}%</span>
                                        @if($voucher->max_discount)
                                            <span class="block text-[10px] text-slate-400 font-normal">Maks: Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</span>
                                        @endif
                                    @else
                                        <span class="text-emerald-600">Rp {{ number_format($voucher->amount, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="p-4 text-slate-600">
                                    @if($voucher->min_spend > 0)
                                        Rp {{ number_format($voucher->min_spend, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400 italic">Tanpa Min. Belanja</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-slate-900">{{ $voucher->used_count }}</span>
                                        <span class="text-slate-400 font-normal">/ {{ $voucher->usage_limit ?? '∞' }} terpakai</span>
                                    </div>
                                </td>
                                <td class="p-4 text-slate-500 text-[11px]">
                                    @if($voucher->expires_at)
                                        <span>s/d {{ $voucher->expires_at->format('d M Y') }}</span>
                                    @else
                                        <span class="text-slate-400 italic">Selamanya</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <form action="{{ route('admin.vouchers.toggle', $voucher) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        @if(!$voucher->is_active)
                                            <button type="submit" title="Klik untuk mengaktifkan" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-[10px] uppercase transition flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                <span>Nonaktif</span>
                                            </button>
                                        @elseif($status === 'expired')
                                            <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-600 font-bold text-[10px] uppercase border border-rose-200">
                                                Kadaluarsa
                                            </span>
                                        @elseif($status === 'exhausted')
                                            <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 font-bold text-[10px] uppercase border border-amber-200">
                                                Kuota Habis
                                            </span>
                                        @else
                                            <button type="submit" title="Klik untuk menonaktifkan" class="px-2.5 py-1 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[10px] uppercase transition flex items-center gap-1 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Aktif</span>
                                            </button>
                                        @endif
                                    </form>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            <span>Edit</span>
                                        </a>
                                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Hapus voucher {{ $voucher->code }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-[11px] transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700">
                                            @if(request('search') || request('status'))
                                                Tidak ada voucher yang cocok dengan filter pencarian.
                                            @else
                                                Belum ada voucher diskon dibuat.
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-400 max-w-sm">
                                            Buat kode kupon diskon menarik untuk meningkatkan konversi penjualan produk sepatu Anda.
                                        </p>
                                        <a href="{{ route('admin.vouchers.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-2xs">
                                            Tambah Voucher Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($vouchers->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $vouchers->links() }}
                </div>
            @endif
        </div>

    </div>

@endsection
