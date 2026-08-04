@extends('layouts.main')

@section('title', 'Pesanan Saya — Sepatu.id')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black uppercase tracking-tight text-neutral-900 mb-8">
            Riwayat Pesanan Saya
        </h1>

        @if($orders->isEmpty())
            <div class="bg-[#f8f8f8] rounded-2xl p-12 border border-neutral-200 text-center max-w-md mx-auto">
                <h3 class="text-base font-bold uppercase tracking-wider text-neutral-900">Belum Ada Pesanan</h3>
                <p class="text-xs text-neutral-500 mt-1">Anda belum melakukan transaksi pembelian di Sepatu.id.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block mt-6 px-8 py-3 rounded-full bg-neutral-900 text-white font-extrabold text-xs uppercase tracking-widest hover:bg-orange-500 transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-2xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <span class="font-black text-neutral-900 text-base">Order #{{ $order->id }}</span>
                                <span class="text-xs text-neutral-400 font-semibold">• {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>

                            <div class="flex items-center gap-2">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-900 border-amber-300',
                                        'diproses' => 'bg-blue-100 text-blue-900 border-blue-300',
                                        'dikirim' => 'bg-indigo-100 text-indigo-900 border-indigo-300',
                                        'selesai' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold border uppercase tracking-wider {{ $statusClasses[$order->status] ?? 'bg-neutral-100 text-neutral-800' }}">
                                    Status: {{ $order->status }}
                                </span>
                            </div>

                            <p class="text-xs text-neutral-500 font-semibold">
                                {{ $order->items->count() }} jenis barang • Total: <strong class="text-neutral-900 font-black">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                            </p>
                        </div>

                        <a href="{{ route('orders.show', $order) }}" class="px-6 py-2.5 rounded-full bg-neutral-900 hover:bg-orange-500 text-white text-xs font-extrabold uppercase tracking-widest transition">
                            Detail &amp; Pelacakan ➔
                        </a>
                    </div>
                @endforeach

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>

@endsection
