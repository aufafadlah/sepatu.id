@extends('layouts.admin')

@section('title', 'Laporan Penjualan — Admin Sepatu.id')
@section('page_title', 'Laporan Penjualan')

@section('content')

    <div class="space-y-6" x-data="{
        importModal: false,
        activePeriod: '{{ $period }}'
    }">

        <!-- Top Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Laporan Rekapitulasi Penjualan</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Analisis pendapatan omzet toko, volume pesanan, cetak dokumen PDF, serta ekspor/impor data.</p>
            </div>

            <!-- Export & Import Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span>Download PDF</span>
                </a>

                <a href="{{ route('admin.laporan.export-csv', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    <span>Ekspor CSV</span>
                </a>

                <button type="button" @click="importModal = true"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    <span>Impor Laporan</span>
                </button>
            </div>
        </div>

        <!-- 4 Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Omzet -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Omzet Pendapatan</span>
                <p class="text-xl font-black text-slate-900 tracking-tight">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
                <span class="text-[10px] text-emerald-600 font-bold block">{{ $periodLabel }}</span>
            </div>

            <!-- Total Transaksi -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pesanan</span>
                <p class="text-xl font-black text-indigo-600 tracking-tight">
                    {{ number_format($totalOrders, 0, ',', '.') }} Pesanan
                </p>
                <span class="text-[10px] text-slate-500 font-medium block">Transaksi dalam filter</span>
            </div>

            <!-- Rata-rata Transaksi -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rata-rata Transaksi</span>
                <p class="text-xl font-black text-slate-900 tracking-tight">
                    Rp {{ number_format($averageOrderValue, 0, ',', '.') }}
                </p>
                <span class="text-[10px] text-slate-500 font-medium block">Per transaksi pesanan</span>
            </div>

            <!-- Pasang Sepatu Terjual -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sepatu Terjual</span>
                <p class="text-xl font-black text-orange-600 tracking-tight">
                    {{ number_format($totalPairsSold, 0, ',', '.') }} Pasang
                </p>
                <span class="text-[10px] text-slate-500 font-medium block">Volume fisik terjual</span>
            </div>
        </div>

        <!-- Filter Controls: Harian, Bulanan, Tahunan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pilih Filter Periode:</span>
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-bold">
                        <button type="button" @click="activePeriod = 'daily'" :class="activePeriod === 'daily' ? 'bg-white text-indigo-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1 rounded-lg transition">
                            Harian
                        </button>
                        <button type="button" @click="activePeriod = 'monthly'" :class="activePeriod === 'monthly' ? 'bg-white text-indigo-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1 rounded-lg transition">
                            Bulanan
                        </button>
                        <button type="button" @click="activePeriod = 'yearly'" :class="activePeriod === 'yearly' ? 'bg-white text-indigo-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1 rounded-lg transition">
                            Tahunan
                        </button>
                    </div>
                </div>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                    {{ $periodLabel }}
                </span>
            </div>

            <!-- Form Filter Dynamic -->
            <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-wrap items-end gap-4 text-xs font-semibold">
                <input type="hidden" name="period" :value="activePeriod">

                <!-- Filter Harian (Date Input) -->
                <div x-show="activePeriod === 'daily'" class="space-y-1">
                    <label class="block text-slate-600">Pilih Tanggal:</label>
                    <input type="date" name="date" value="{{ $date }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                </div>

                <!-- Filter Bulanan (Month & Year) -->
                <div x-show="activePeriod === 'monthly'" class="flex items-center gap-2">
                    <div class="space-y-1">
                        <label class="block text-slate-600">Bulan:</label>
                        <select name="month" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-slate-600">Tahun:</label>
                        <select name="year" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                            @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Filter Tahunan (Year) -->
                <div x-show="activePeriod === 'yearly'" class="space-y-1">
                    <label class="block text-slate-600">Pilih Tahun:</label>
                    <select name="year" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        @for($y = now()->year; $y >= now()->year - 4; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="space-y-1">
                    <label class="block text-slate-600">Status Pesanan:</label>
                    <select name="status" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Hanya Selesai (Omzet Sah)</option>
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status Pesanan</option>
                        <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="dikirim" {{ $status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-2xs">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <!-- Table Laporan Penjualan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 uppercase font-semibold tracking-wider text-[10px]">
                        <tr>
                            <th class="p-4 w-12 text-center">No.</th>
                            <th class="p-4">ID Pesanan</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Pelanggan / Penerima</th>
                            <th class="p-4">Item Produk Sepatu</th>
                            <th class="p-4">Metode Bayar</th>
                            <th class="p-4">Status Pesanan</th>
                            <th class="p-4 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- No -->
                                <td class="p-4 text-center font-bold text-slate-400">
                                    {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                                </td>

                                <!-- Order ID -->
                                <td class="p-4 font-bold text-slate-900">
                                    <a href="{{ route('admin.pesanan.show', $order) }}" class="hover:text-indigo-600 transition">
                                        #{{ $order->id }}
                                    </a>
                                </td>

                                <!-- Tanggal -->
                                <td class="p-4 text-slate-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>

                                <!-- Pelanggan -->
                                <td class="p-4">
                                    <span class="font-bold text-slate-900 block">{{ $order->recipient_name ?? ($order->user->name ?? 'Pelanggan') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $order->recipient_phone ?? ($order->user->phone ?? '') }}</span>
                                </td>

                                <!-- Items -->
                                <td class="p-4">
                                    <ul class="space-y-1">
                                        @foreach($order->items as $item)
                                            <li class="text-[11px] text-slate-700">
                                                • {{ $item->productVariant->product->name ?? 'Sepatu' }}
                                                (EUR {{ $item->productVariant->size ?? '-' }}) × {{ $item->qty }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <!-- Payment Method -->
                                <td class="p-4 text-slate-600 font-semibold">
                                    {{ $order->paymentMethodLabel() }}
                                </td>

                                <!-- Status -->
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                        {{ $order->status === 'selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' :
                                           ($order->status === 'dibatalkan' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                <!-- Total -->
                                <td class="p-4 text-right font-black text-slate-900">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400 font-medium text-xs">
                                    Tidak ada data transaksi penjualan pada periode {{ $periodLabel }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-slate-200/80 font-bold text-xs text-slate-900">
                        <tr>
                            <td colspan="7" class="p-4 text-right uppercase tracking-wider">
                                Total Omzet Keseluruhan Periode Ini:
                            </td>
                            <td class="p-4 text-right text-base text-indigo-700">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        </div>

        <!-- Modal Impor Data Laporan CSV -->
        <div x-show="importModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" @click.self="importModal = false">
            <div x-show="importModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-4">
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">
                        Impor Data Laporan CSV
                    </h3>
                    <button type="button" @click="importModal = false" class="text-slate-400 hover:text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('admin.laporan.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold">
                    @csrf
                    <div>
                        <label class="block text-slate-700 mb-1.5">Pilih Berkas CSV (.csv): <span class="text-rose-500">*</span></label>
                        <input type="file" name="file" required accept=".csv,text/csv" class="w-full text-xs text-slate-500 border border-slate-200 rounded-xl p-2.5 bg-slate-50">
                        <p class="text-[11px] text-slate-400 mt-1">Gunakan format file CSV dengan pemisah koma (,). Ukuran maksimal 5MB.</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1 text-[11px] text-slate-600">
                        <span class="font-bold text-slate-800 block">Informasi Struktur Kolom:</span>
                        <p>Format yang didukung mencakup data transaksi dengan kolom: No, ID Pesanan, Tanggal, Nama, WhatsApp, Alamat, Metode, Status, Total.</p>
                    </div>

                    <div class="pt-2 flex items-center gap-3">
                        <button type="button" @click="importModal = false" class="w-1/2 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="w-1/2 py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-sm">
                            Unggah &amp; Impor
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

@endsection
