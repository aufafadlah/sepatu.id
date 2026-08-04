@extends('layouts.main')

@section('title', 'Detail Pesanan #' . $order->id . ' — Sepatu.id')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('orders.index') }}" class="text-xs font-extrabold uppercase tracking-wider text-neutral-600 hover:text-neutral-900 flex items-center gap-1">
                ← Kembali ke Pesanan Saya
            </a>
            <span class="text-xs font-extrabold text-neutral-400">Order ID: #{{ $order->id }}</span>
        </div>

        <div class="bg-white rounded-2xl p-6 sm:p-10 border border-neutral-200 shadow-2xs space-y-8">
            <!-- Header Status -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-neutral-100 pb-6">
                <div>
                    <h1 class="text-2xl font-black uppercase tracking-tight text-neutral-900">Pesanan #{{ $order->id }}</h1>
                    <p class="text-xs text-neutral-500 font-semibold mt-0.5">Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                </div>

                <div>
                    @php
                        $statusClasses = [
                            'pending' => 'bg-amber-100 text-amber-900 border-amber-300',
                            'diproses' => 'bg-blue-100 text-blue-900 border-blue-300',
                            'dikirim' => 'bg-indigo-100 text-indigo-900 border-indigo-300',
                            'selesai' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                        ];
                    @endphp
                    <span class="px-4 py-2 rounded-full text-xs font-extrabold border uppercase tracking-wider {{ $statusClasses[$order->status] ?? 'bg-neutral-100 text-neutral-800' }}">
                        Status: {{ $order->status }}
                    </span>
                </div>
            </div>

            <!-- Items Table -->
            <div>
                <h4 class="text-xs font-extrabold text-neutral-900 uppercase tracking-widest mb-4">Rincian Barang</h4>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-[#f8f8f8] border border-neutral-200 text-xs">
                            <div>
                                <h5 class="font-extrabold text-neutral-900 text-sm uppercase">
                                    {{ $item->productVariant->product->name ?? 'Produk Sepatu' }}
                                </h5>
                                <p class="text-neutral-500 font-semibold mt-0.5">
                                    Ukuran EUR {{ $item->productVariant->size ?? '-' }} × {{ $item->qty }} pasang
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-neutral-400 block text-[10px] uppercase font-bold">Harga Satuan</span>
                                <span class="font-black text-neutral-900">
                                    Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="p-5 rounded-xl bg-[#f8f8f8] border border-neutral-200 text-xs space-y-2">
                <h4 class="font-extrabold text-neutral-900 uppercase tracking-wider">Alamat Tujuan Pengiriman:</h4>
                <p class="text-neutral-700 leading-relaxed font-medium">
                    {{ $order->shipping_address }}
                </p>
            </div>

            <!-- Total Payment -->
            <div class="pt-4 border-t border-neutral-100 flex items-center justify-between text-neutral-900">
                <span class="text-xs font-extrabold uppercase tracking-wider">Total Tagihan Pembayaran:</span>
                <span class="text-2xl font-black">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

@endsection
