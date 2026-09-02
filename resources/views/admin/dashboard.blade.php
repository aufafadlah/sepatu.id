@extends('layouts.admin')

@section('title', 'Dashboard — Admin Sepatu.id')
<!-- @section('page_title', 'Dashboard Overview') -->

@section('content')

    <div class="space-y-6">

        <!-- Top Header & Export PDF Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Ringkasan Performa Toko</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Monitoring penjualan, persediaan stok, dan aktivitas admin secara real-time.</p>
            </div>
            <a href="{{ route('admin.laporan.export') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export Laporan Penjualan PDF</span>
            </a>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Penjualan Selesai</span>
                <p class="text-2xl font-black text-slate-900">
                    Rp {{ number_format($totalSales, 0, ',', '.') }}
                </p>
                <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">Terverifikasi</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Pesanan</span>
                <p class="text-2xl font-black text-slate-900">{{ number_format($totalOrders) }}</p>
                <span class="text-xs text-slate-500 font-medium">{{ $pendingOrders }} pesanan butuh proses</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Produk Sepatu</span>
                <p class="text-2xl font-black text-slate-900">{{ number_format($totalProducts) }}</p>
                <span class="text-xs text-slate-500 font-medium">Aktif di katalog</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Pengguna</span>
                <p class="text-2xl font-black text-slate-900">{{ number_format($totalUsers) }}</p>
                <span class="text-xs text-slate-500 font-medium">Pelanggan &amp; Staf Admin</span>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Low Stock Warning Table (Left) -->
            <div class="lg:col-span-6 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span>Stok Menipis (&lt; 5 Pasang)</span>
                    </h3>
                    <a href="{{ route('admin.gudang.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline flex items-center gap-1">
                        <span>Kelola Stok</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                <th class="pb-2">Produk</th>
                                <th class="pb-2">Ukuran</th>
                                <th class="pb-2 text-right">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($lowStockVariants as $variant)
                                <tr>
                                    <td class="py-2.5 font-bold text-slate-900">{{ $variant->product->name ?? 'Produk' }}</td>
                                    <td class="py-2.5 text-slate-500">EUR {{ $variant->size }}</td>
                                    <td class="py-2.5 text-right font-bold text-rose-600">{{ $variant->stock }} pasang</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-slate-400 text-xs">Semua varian stok dalam kondisi aman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Log Audit Trail (Right) -->
            <div class="lg:col-span-6 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span>Audit Trail Activity Log</span>
                    </h3>
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-1 text-xs">
                    @forelse($recentActivities as $log)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-xs">{{ $log->user->name ?? 'Sistem' }}</span>
                                <span class="text-[10px] text-slate-400 font-semibold">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-600 font-medium text-[11px]">{{ $log->description }}</p>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs text-center py-4">Belum ada riwayat aktivitas tercatat.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

@endsection
