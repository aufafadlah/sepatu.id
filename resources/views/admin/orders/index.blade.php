@extends('layouts.admin')

@section('title', 'Pesanan Pelanggan — Admin Sepatu.id')
@section('page_title', 'Pesanan Pelanggan')

@section('content')

    <div class="space-y-6" x-data="{
        cancelModal: false,
        cancelOrderId: null,
        openCancelModal(id) {
            this.cancelOrderId = id;
            this.cancelModal = true;
        }
    }">

        <!-- Top Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Kelola Pesanan Pelanggan</h2>
                    @if($unviewedCount > 0)
                        <span class="px-2.5 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-black uppercase animate-pulse">
                            {{ $unviewedCount }} Baru
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Pantau transaksi, verifikasi bukti transfer, cetak struk label, dan kelola status pengiriman.</p>
            </div>

            <div class="flex items-center gap-2">
                <form action="{{ route('admin.pesanan.auto-cancel') }}" method="POST" onsubmit="return confirm('Jalankan pemeriksaan auto-cancel untuk membatalkan pesanan transfer yang melewati batas 5 hari?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold transition">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Cek Auto-Cancel (5 Hari)</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Filter Tabs & Search Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
            <!-- Search Form -->
            <form action="{{ route('admin.pesanan.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="tab" value="{{ request('tab', 'all') }}">
                <div class="relative flex-grow max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Pesanan (#12), nama penerima, no. WA, atau email..."
                           class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                </div>

                <select name="status" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    <option value="">Semua Status Pengiriman</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition">
                    Cari &amp; Filter
                </button>

                @if(request('search') || request('status') || (request('tab') && request('tab') !== 'all'))
                    <a href="{{ route('admin.pesanan.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                        Reset Filter
                    </a>
                @endif
            </form>

            <!-- Status Tabs -->
            <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-slate-100 text-xs font-semibold">
                <a href="{{ route('admin.pesanan.index', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}"
                   class="px-3.5 py-1.5 rounded-xl transition flex items-center gap-1.5 {{ !request('tab') || request('tab') == 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <span>Semua</span>
                </a>

                <a href="{{ route('admin.pesanan.index', array_merge(request()->except('tab', 'page'), ['tab' => 'unviewed'])) }}"
                   class="px-3.5 py-1.5 rounded-xl transition flex items-center gap-1.5 {{ request('tab') == 'unviewed' ? 'bg-rose-600 text-white font-bold shadow-xs' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}">
                    <span class="w-2 h-2 rounded-full bg-rose-500 {{ request('tab') == 'unviewed' ? 'bg-white' : '' }}"></span>
                    <span>Belum Dilihat</span>
                    @if($unviewedCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('tab') == 'unviewed' ? 'bg-white text-rose-700' : 'bg-rose-600 text-white' }}">{{ $unviewedCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.pesanan.index', array_merge(request()->except('tab', 'page'), ['payment_status' => 'pending_verification'])) }}"
                   class="px-3.5 py-1.5 rounded-xl transition flex items-center gap-1.5 {{ request('payment_status') == 'pending_verification' ? 'bg-amber-600 text-white font-bold shadow-xs' : 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Perlu Verifikasi TF</span>
                    @if($pendingVerificationCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('payment_status') == 'pending_verification' ? 'bg-white text-amber-700' : 'bg-amber-600 text-white' }}">{{ $pendingVerificationCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.pesanan.index', array_merge(request()->except('tab', 'page'), ['tab' => 'unprinted'])) }}"
                   class="px-3.5 py-1.5 rounded-xl transition flex items-center gap-1.5 {{ request('tab') == 'unprinted' ? 'bg-indigo-600 text-white font-bold shadow-xs' : 'bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    <span>Belum Dicetak</span>
                    @if($unprintedCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('tab') == 'unprinted' ? 'bg-white text-indigo-700' : 'bg-indigo-600 text-white' }}">{{ $unprintedCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.pesanan.index', array_merge(request()->except('tab', 'page'), ['tab' => 'active'])) }}"
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('tab') == 'active' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <span>Pesanan Aktif ({{ $activeCount }})</span>
                </a>

                <a href="{{ route('admin.pesanan.index', array_merge(request()->except('tab', 'page'), ['tab' => 'history'])) }}"
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('tab') == 'history' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' }}">
                    <span>Riwayat Selesai ({{ $historyCount }})</span>
                </a>

                <a href="{{ route('admin.pesanan.index', array_merge(request()->except('tab', 'page'), ['tab' => 'cancelled'])) }}"
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('tab') == 'cancelled' ? 'bg-rose-700 text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                    <span>Dibatalkan ({{ $cancelledCount }})</span>
                </a>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 uppercase font-semibold tracking-wider text-[10px]">
                        <tr>
                            <th class="p-4 w-12 text-center">No.</th>
                            <th class="p-4">ID Pesanan</th>
                            <th class="p-4">Pemesan / Penerima</th>
                            <th class="p-4">Metode &amp; Bukti TF</th>
                            <th class="p-4">Total Tagihan</th>
                            <th class="p-4">Status Bayar</th>
                            <th class="p-4">Status Kirim</th>
                            <th class="p-4">Struk Label</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/80 transition {{ !$order->is_viewed_by_admin ? 'bg-indigo-50/30 font-semibold' : '' }}">
                                <!-- No -->
                                <td class="p-4 text-center font-bold text-slate-400">
                                    {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                                </td>

                                <!-- Order ID & Unread Indicator -->
                                <td class="p-4 font-bold text-slate-900">
                                    <div class="flex items-center gap-1.5">
                                        @if(!$order->is_viewed_by_admin)
                                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse" title="Pesanan baru belum dilihat admin"></span>
                                        @endif
                                        <a href="{{ route('admin.pesanan.show', $order) }}" class="hover:text-indigo-600 transition">
                                            #{{ $order->id }}
                                        </a>
                                    </div>
                                    <span class="text-[10px] text-slate-400 block font-normal">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </td>

                                <!-- Customer Info -->
                                <td class="p-4 font-bold text-slate-900">
                                    {{ $order->recipient_name ?? $order->user->name ?? 'Pelanggan' }}
                                    @if($order->recipient_phone || ($order->user->phone ?? null))
                                        <span class="block text-[10px] text-slate-500 font-medium font-mono">
                                            WA: {{ $order->recipient_phone ?? $order->user->phone }}
                                        </span>
                                    @endif
                                    <span class="block text-[10px] text-slate-400 font-normal truncate max-w-[150px]">{{ $order->user->email ?? '' }}</span>
                                </td>

                                <!-- Payment Method & Proof -->
                                <td class="p-4">
                                    <span class="font-semibold text-slate-700 block">{{ $order->paymentMethodLabel() }}</span>
                                    @if($order->payment_proof)
                                        <a href="{{ route('admin.pesanan.show', $order) }}#bukti-transfer" class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-bold text-[10px] border border-indigo-200 hover:bg-indigo-100 transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            <span>Lihat TF</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[10px] italic">Tanpa bukti</span>
                                    @endif
                                </td>

                                <!-- Total Bill -->
                                <td class="p-4 font-bold text-slate-900">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                    @if($order->discount_amount > 0)
                                        <span class="block text-[10px] text-emerald-600 font-bold">Disc: -Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                    @endif
                                </td>

                                <!-- Payment Status -->
                                <td class="p-4">
                                    @php
                                        $paymentClasses = [
                                            'unpaid' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'pending_verification' => 'bg-amber-50 text-amber-700 border-amber-200 animate-pulse',
                                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border uppercase {{ $paymentClasses[$order->payment_status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $order->paymentStatusLabel() }}
                                    </span>
                                </td>

                                <!-- Shipping / Order Status -->
                                <td class="p-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'diproses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'dikirim' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'dibatalkan' => 'bg-rose-100 text-rose-800 border-rose-300',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border uppercase {{ $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                <!-- Struk Printed Status -->
                                <td class="p-4">
                                    @if($order->is_printed)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold" title="Dicetak: {{ $order->printed_at ? $order->printed_at->format('d/m/Y H:i') : '-' }}">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                            <span>Sudah Cetak</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Belum Cetak</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.pesanan.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-semibold text-[11px] transition shadow-2xs">
                                            <span>Kelola</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </a>

                                        <a href="{{ route('admin.pesanan.print-address', $order) }}" target="_blank" title="Cetak Label Alamat" class="p-1.5 rounded-xl {{ $order->is_printed ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200' }} font-semibold text-[11px] transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                        </a>

                                        @if($order->status !== \App\Models\Order::STATUS_DIBATALKAN && $order->status !== \App\Models\Order::STATUS_SELESAI)
                                            <button type="button" @click="openCancelModal({{ $order->id }})" title="Batalkan Pesanan" class="p-1.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 font-semibold text-[11px] transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-slate-400 font-medium text-xs">
                                    Tidak ada data transaksi pesanan yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        </div>

        <!-- Modal Konfirmasi Batalkan Pesanan -->
        <div x-show="cancelModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" @click.self="cancelModal = false">
            <div x-show="cancelModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 text-center space-y-4">
                
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>

                <div class="space-y-1">
                    <h3 class="text-base font-bold text-slate-900">
                        Batalkan Pesanan <span x-text="'#' + cancelOrderId"></span>?
                    </h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                        Status pesanan akan diubah menjadi <strong>Dibatalkan</strong> dan stok seluruh varian sepatu pada pesanan ini akan otomatis dikembalikan ke gudang.
                    </p>
                </div>

                <form :action="`/admin/pesanan/${cancelOrderId}/cancel`" method="POST" class="pt-2 flex items-center gap-3">
                    @csrf
                    <button type="button" @click="cancelModal = false" class="w-1/2 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition shadow-sm">
                        Ya, Batalkan
                    </button>
                </form>

            </div>
        </div>

    </div>

@endsection
