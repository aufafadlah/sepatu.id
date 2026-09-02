@extends('layouts.admin')

@section('title', 'Pesanan Pelanggan — Admin Sepatu.id')
@section('page_title', 'Pesanan Pelanggan')

@section('content')

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Kelola Pesanan Pelanggan</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Pantau transaksi, verifikasi bukti transfer pembayaran, dan perbarui status pengiriman.</p>
            </div>

            <!-- Status & Tab Filter Pills -->
            <div class="flex flex-wrap items-center gap-1.5 text-xs font-semibold">
                <a href="{{ route('admin.pesanan.index', ['tab' => 'all']) }}" class="px-3.5 py-1.5 rounded-xl transition {{ !request('tab') || request('tab') == 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>
                <a href="{{ route('admin.pesanan.index', ['tab' => 'active']) }}" class="px-3.5 py-1.5 rounded-xl transition {{ request('tab') == 'active' ? 'bg-indigo-600 text-white font-bold' : 'bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100' }}">
                    Pesanan Aktif (Berjalan)
                </a>
                <a href="{{ route('admin.pesanan.index', ['tab' => 'history']) }}" class="px-3.5 py-1.5 rounded-xl transition {{ request('tab') == 'history' ? 'bg-emerald-600 text-white font-bold' : 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' }}">
                    Riwayat Selesai
                </a>
                <a href="{{ route('admin.pesanan.index', ['payment_status' => 'pending_verification']) }}" class="px-3.5 py-1.5 rounded-xl transition flex items-center gap-1 {{ request('payment_status') == 'pending_verification' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Perlu Verifikasi TF</span>
                </a>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 uppercase font-semibold tracking-wider text-[10px]">
                        <tr>
                            <th class="p-4">ID Pesanan</th>
                            <th class="p-4">Pemesan</th>
                            <th class="p-4">Metode Bayar</th>
                            <th class="p-4">Bukti TF</th>
                            <th class="p-4">Total Tagihan</th>
                            <th class="p-4">Status Pembayaran</th>
                            <th class="p-4">Status Pengiriman</th>
                            <th class="p-4 text-center">Aksi Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 font-bold text-slate-900">
                                    <a href="{{ route('admin.pesanan.show', $order) }}" class="hover:text-indigo-600 transition flex items-center gap-1">
                                        <span>#{{ $order->id }}</span>
                                    </a>
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    {{ $order->user->name ?? 'Pelanggan' }}
                                    @if($order->recipient_name && $order->recipient_name !== ($order->user->name ?? ''))
                                        <span class="block text-[10px] text-indigo-600 font-semibold">Penerima: {{ $order->recipient_name }}</span>
                                    @endif
                                    <span class="block text-[10px] text-slate-400 font-normal">{{ $order->recipient_phone ?? $order->user->phone ?? $order->user->email ?? '' }}</span>
                                </td>
                                <td class="p-4 font-semibold text-slate-700">
                                    {{ $order->paymentMethodLabel() }}
                                </td>
                                <td class="p-4">
                                    @if($order->payment_proof)
                                        <a href="{{ route('admin.pesanan.show', $order) }}#bukti-transfer" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-[10px] border border-indigo-200 hover:bg-indigo-100 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            <span>Lihat TF</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[10px] italic">Belum ada</span>
                                    @endif
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                    @if($order->discount_amount > 0)
                                        <span class="block text-[10px] text-emerald-600 font-bold">Kupon: -Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @php
                                        $paymentClasses = [
                                            'unpaid' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'pending_verification' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border uppercase {{ $paymentClasses[$order->payment_status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $order->paymentStatusLabel() }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'diproses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'dikirim' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border uppercase {{ $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.pesanan.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-semibold text-[11px] transition">
                                            <span>Kelola</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </a>
                                        <a href="{{ route('admin.pesanan.print-address', $order) }}" target="_blank" title="Cetak Alamat Pengiriman" class="p-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400 font-medium text-xs">
                                    Belum ada transaksi pesanan pelanggan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom Brand Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        </div>

    </div>

@endsection
