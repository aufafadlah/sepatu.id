<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Pengiriman #{{ $order->id }} — Sepatu.id</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
            }
            .print-container {
                box-shadow: none !important;
                border: 2px solid #000000 !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 20px !important;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8 flex flex-col items-center justify-center min-h-screen">

    <!-- Action Bar (Hide when printing) -->
    <div class="no-print w-full max-w-2xl mb-6 flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs">
        <button onclick="window.close()" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Tutup Halaman</span>
        </button>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                <span>Cetak Label Alamat (Print)</span>
            </button>
        </div>
    </div>

    <!-- Printable Shipping Label Card -->
    <div class="print-container w-full max-w-2xl bg-white rounded-2xl border-2 border-slate-900 p-6 sm:p-8 shadow-md text-slate-900 space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-black text-lg flex items-center justify-center shrink-0">
                    S
                </div>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight uppercase">Sepatu.id</h1>
                    <p class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">Official Store &amp; Warehouse</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-block text-xs font-black bg-slate-100 text-slate-900 px-3 py-1 rounded-lg border border-slate-300 font-mono">
                    ORDER #{{ $order->id }}
                </span>
                <p class="text-[10px] text-slate-500 font-bold mt-1">Tgl: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Pengirim & Penerima Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <!-- Box Pengirim -->
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-2 text-xs">
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block">DARI (PENGIRIM):</span>
                <p class="font-extrabold text-slate-900 text-sm">Sepatu.id Official Store</p>
                <p class="text-slate-600 font-medium">No. Telp/WA: <strong>0812-3456-7890</strong></p>
                <p class="text-slate-600 leading-relaxed font-medium">Jl. Malioboro No. 45, Kebayoran Baru, Jakarta Selatan, 12110</p>
            </div>

            <!-- Box Penerima -->
            <div class="p-4 rounded-xl border-2 border-slate-900 bg-amber-50/40 space-y-2 text-xs">
                <span class="text-[10px] font-black uppercase text-amber-700 tracking-wider block">KEPADA (PENERIMA):</span>
                <p class="font-black text-slate-900 text-base">
                    {{ $order->recipient_name ?? $order->user->name }}
                </p>
                <p class="text-slate-900 font-bold">
                    No. Telp / WA: <span class="text-sm font-black">{{ $order->recipient_phone ?? $order->user->phone ?? '-' }}</span>
                </p>
                <div class="pt-2 border-t border-slate-300/80">
                    <p class="text-slate-900 font-semibold leading-relaxed">
                        {{ $order->shipping_address }}
                    </p>
                </div>
            </div>

        </div>

        <!-- Catatan Khusus Pengiriman -->
        @if($order->notes)
            <div class="p-3.5 rounded-xl bg-slate-100 border border-slate-300 text-xs">
                <span class="font-extrabold text-slate-900 uppercase block mb-0.5">Catatan Khusus Kurir / Pembeli:</span>
                <p class="text-slate-700 italic font-medium">"{{ $order->notes }}"</p>
            </div>
        @endif

        <!-- Rincian Isi Paket (Daftar Sepatu) -->
        <div class="space-y-2">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Rincian Isi Paket Sepatu:</h3>
            <table class="w-full text-xs text-left border-collapse border border-slate-300">
                <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="border border-slate-300 p-2">Nama Produk Sepatu</th>
                        <th class="border border-slate-300 p-2 text-center w-24">Ukuran</th>
                        <th class="border border-slate-300 p-2 text-center w-16">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="border border-slate-300 p-2 font-bold text-slate-900">
                                {{ $item->productVariant->product->name ?? 'Produk Sepatu' }}
                            </td>
                            <td class="border border-slate-300 p-2 text-center font-bold">
                                Size {{ $item->productVariant->size ?? '-' }}
                            </td>
                            <td class="border border-slate-300 p-2 text-center font-bold">
                                {{ $item->quantity }}x
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer Info kurir -->
        <div class="pt-4 border-t-2 border-slate-900 flex items-center justify-between text-xs font-bold text-slate-700">
            <div>
                Metode Pembayaran: <span class="uppercase font-extrabold text-slate-900">{{ $order->paymentMethodLabel() }}</span>
            </div>
            <div class="text-right">
                Status Pembayaran: 
                <span class="uppercase font-black text-slate-900">
                    {{ $order->paymentStatusLabel() }}
                </span>
            </div>
        </div>

    </div>

    <!-- Auto Print Script -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Auto open print dialog after brief delay
            setTimeout(() => {
                window.print();
            }, 400);
        });
    </script>

</body>
</html>
