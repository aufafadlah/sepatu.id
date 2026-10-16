@extends('layouts.main')

@section('title', 'Pesanan Saya — Sepatu.id')

@section('content')

    <div x-data="{ confirmModal: false, confirmAction: '', orderLabel: '' }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-200 pb-6">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tight text-neutral-900">
                    Daftar Pesanan Saya
                </h1>
                <p class="text-xs text-neutral-500 font-semibold mt-1">Lacak status pesanan berjalan dan lihat riwayat belanja sepatu Anda.</p>
            </div>

            <!-- Tab Navigation (Separated Active Orders & History) -->
            <div class="flex items-center gap-2 bg-[#f5f5f5] p-1.5 rounded-full border border-neutral-200 text-xs font-extrabold uppercase tracking-wider">
                <a href="{{ route('orders.index', ['tab' => 'all']) }}"
                   class="px-5 py-2 rounded-full transition {{ ($currentTab ?? 'all') === 'all' ? 'bg-neutral-900 text-white shadow-xs' : 'text-neutral-600 hover:text-neutral-900' }}">
                    Semua
                </a>
                <a href="{{ route('orders.index', ['tab' => 'active']) }}"
                   class="px-5 py-2 rounded-full transition flex items-center gap-1.5 {{ ($currentTab ?? '') === 'active' ? 'bg-neutral-900 text-white shadow-xs' : 'text-neutral-600 hover:text-neutral-900' }}">
                    <span>Pesanan Aktif</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ ($currentTab ?? '') === 'active' ? 'bg-orange-500 text-white' : 'bg-neutral-200 text-neutral-700' }}">{{ $activeCount ?? 0 }}</span>
                </a>
                <a href="{{ route('orders.index', ['tab' => 'history']) }}"
                   class="px-5 py-2 rounded-full transition flex items-center gap-1.5 {{ ($currentTab ?? '') === 'history' ? 'bg-neutral-900 text-white shadow-xs' : 'text-neutral-600 hover:text-neutral-900' }}">
                    <span>Riwayat Selesai</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ ($currentTab ?? '') === 'history' ? 'bg-emerald-600 text-white' : 'bg-neutral-200 text-neutral-700' }}">{{ $historyCount ?? 0 }}</span>
                </a>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="bg-[#f8f8f8] rounded-2xl p-12 border border-neutral-200 text-center max-w-md mx-auto">
                <h3 class="text-base font-bold uppercase tracking-wider text-neutral-900">
                    {{ ($currentTab ?? '') === 'history' ? 'Belum Ada Riwayat Pesanan' : (($currentTab ?? '') === 'active' ? 'Tidak Ada Pesanan Aktif' : 'Belum Ada Pesanan') }}
                </h3>
                <p class="text-xs text-neutral-500 mt-1">
                    {{ ($currentTab ?? '') === 'history' ? 'Pesanan yang telah Anda selesaikan akan muncul di sini.' : 'Anda belum memiliki transaksi pesanan yang sedang berjalan.' }}
                </p>
                <a href="{{ route('catalog.index') }}" class="inline-block mt-6 px-8 py-3 rounded-full bg-neutral-900 text-white font-extrabold text-xs uppercase tracking-widest hover:bg-orange-500 transition">
                    Mulai Belanja Sepatu
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-2xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6 hover:border-neutral-300 transition">
                        
                        <div class="space-y-3 flex-grow">
                            <!-- Header Info -->
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="font-black text-neutral-900 text-base">Order #{{ $order->id }}</span>
                                <span class="text-xs text-neutral-400 font-semibold">• {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                                
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-900 border-amber-300',
                                        'diproses' => 'bg-blue-100 text-blue-900 border-blue-300',
                                        'dikirim' => 'bg-indigo-100 text-indigo-900 border-indigo-300',
                                        'selesai' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                                    ];

                                    $paymentClasses = [
                                        'unpaid' => 'bg-rose-100 text-rose-800 border-rose-300',
                                        'pending_verification' => 'bg-amber-100 text-amber-900 border-amber-300',
                                        'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold border uppercase tracking-wider {{ $statusClasses[$order->status] ?? 'bg-neutral-100 text-neutral-800' }}">
                                    Status: {{ $order->status }}
                                </span>

                                <span class="px-3 py-1 rounded-full text-[11px] font-bold border uppercase tracking-wider {{ $paymentClasses[$order->payment_status] ?? 'bg-neutral-100 text-neutral-800' }}">
                                    Bayar: {{ $order->paymentStatusLabel() }}
                                </span>
                            </div>

                            <!-- Shoes Image Thumbnails Gallery -->
                            <div class="flex items-center gap-3 overflow-x-auto py-1">
                                @foreach($order->items->take(4) as $item)
                                    @php $prod = $item->productVariant->product ?? null; @endphp
                                    <div class="flex items-center gap-2 p-2 rounded-xl bg-[#f8f8f8] border border-neutral-200 shrink-0">
                                        <div class="w-12 h-12 rounded-lg bg-white p-1 flex items-center justify-center border border-neutral-100">
                                            @if($prod && $prod->image)
                                                <img src="{{ Str::startsWith($prod->image, ['http://', 'https://']) ? $prod->image : asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}" class="max-h-full max-w-full object-contain">
                                            @else
                                                <svg class="w-6 h-6 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                        <div class="text-[11px]">
                                            <p class="font-extrabold text-neutral-900 max-w-[120px] truncate uppercase">{{ $prod->name ?? 'Produk' }}</p>
                                            <p class="text-neutral-400 font-semibold">EUR {{ $item->productVariant->size ?? '-' }} × {{ $item->qty }}</p>
                                        </div>
                                    </div>
                                @endforeach

                                @if($order->items->count() > 4)
                                    <span class="text-xs text-neutral-400 font-bold bg-[#f8f8f8] px-3 py-3 rounded-xl border border-neutral-200">
                                        +{{ $order->items->count() - 4 }} lagi
                                    </span>
                                @endif
                            </div>

                            <p class="text-xs text-neutral-500 font-semibold">
                                Total Pembayaran: <strong class="text-neutral-900 font-black text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</strong> ({{ $order->paymentMethodLabel() }})
                            </p>
                        </div>

                        <!-- Action Button -->
                        <div class="flex flex-col sm:flex-row md:flex-col gap-2 shrink-0 w-full sm:w-auto">
                            <a href="{{ route('orders.show', $order) }}" class="px-6 py-3 rounded-full bg-neutral-900 hover:bg-orange-500 text-white text-xs font-extrabold uppercase tracking-widest transition text-center flex items-center justify-center gap-1.5">
                                <span>Detail &amp; Lacak</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>

                            @if($order->status !== \App\Models\Order::STATUS_SELESAI && $order->status === \App\Models\Order::STATUS_DIKIRIM)
                                <button type="button"
                                        @click="confirmModal = true; confirmAction = '{{ route('orders.confirm-received', $order) }}'; orderLabel = 'Pesanan #{{ $order->id }}'"
                                        class="w-full px-5 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-extrabold uppercase tracking-wider transition text-center flex items-center justify-center gap-1 shadow-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                    <span>Terima Pesanan</span>
                                </button>
                            @endif

                            @if($order->payment_status === 'unpaid' && $order->payment_method === 'transfer')
                                <a href="{{ route('orders.show', $order) }}#upload-bukti" class="px-6 py-2 rounded-full bg-orange-500 hover:bg-orange-600 text-white text-[11px] font-extrabold uppercase tracking-wider transition text-center">
                                    Upload Bukti Bayar
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif

        <!-- Custom UI Modal Konfirmasi Pesanan Diterima -->
        <div x-show="confirmModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-neutral-950/60 backdrop-blur-xs" @click.self="confirmModal = false">
            <div x-show="confirmModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-neutral-200 text-center space-y-5">
                
                <!-- Icon -->
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="space-y-2">
                    <h3 class="text-lg font-black uppercase tracking-tight text-neutral-900">
                        Konfirmasi Pesanan Diterima
                    </h3>
                    <p class="text-xs text-neutral-600 font-medium leading-relaxed">
                        Apakah paket sepatu untuk <span class="font-bold text-neutral-900" x-text="orderLabel"></span> sudah Anda terima dengan kondisi baik?
                    </p>
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-[11px] text-emerald-800 text-left font-medium flex items-start gap-2 mt-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Setelah dikonfirmasi, status pesanan otomatis berubah menjadi <strong>Selesai</strong> dan Anda dapat memberikan ulasan bintang &amp; foto/video produk.</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <form :action="confirmAction" method="POST" class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                    @csrf
                    <button type="button" @click="confirmModal = false" class="w-full sm:w-1/2 py-3 px-4 rounded-full border border-neutral-300 text-neutral-700 font-bold text-xs uppercase tracking-wider hover:bg-neutral-100 transition">
                        Belum Diterima
                    </button>
                    <button type="submit" class="w-full sm:w-1/2 py-3 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider transition shadow-md flex items-center justify-center gap-1.5">
                        <span>Ya, Diterima</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </button>
                </form>

            </div>
        </div>

    </div>

@endsection

