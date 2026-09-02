@extends('layouts.admin')

@section('title', 'Stok Gudang — Admin Sepatu.id')
@section('page_title', 'Stok Gudang')

@section('content')

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Manajemen Stok Fisik Gudang</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Pantau dan perbarui stok sepatu secara real-time per varian ukuran.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.gudang.index', ['low_stock' => 1]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('low_stock') ? 'bg-rose-600 text-white shadow-2xs' : 'bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span>Filter Stok Menipis (&lt; 5)</span>
                </a>
                @if(request('low_stock') || request('search'))
                    <a href="{{ route('admin.gudang.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        <span>Reset Filter</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
            <form action="{{ route('admin.gudang.index') }}" method="GET" class="flex items-center gap-3">
                @if(request('low_stock'))
                    <input type="hidden" name="low_stock" value="1">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk sepatu..." class="w-full max-w-md px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Variants Stock Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 uppercase font-semibold tracking-wider text-[10px]">
                        <tr>
                            <th class="p-4">Produk Sepatu</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Ukuran EUR</th>
                            <th class="p-4">Status Stok</th>
                            <th class="p-4 text-right">Update Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse($variants as $variant)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 font-bold text-slate-900">
                                    {{ $variant->product->name ?? 'Produk' }}
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-[10px] font-semibold text-slate-600">
                                        {{ $variant->product->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="p-4 font-bold text-slate-800">
                                    EUR {{ $variant->size }}
                                </td>
                                <td class="p-4">
                                    @if($variant->stock <= 0)
                                        <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-600 text-[10px] font-bold">Habis (0)</span>
                                    @elseif($variant->stock < 5)
                                        <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold">Menipis ({{ $variant->stock }})</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">Aman ({{ $variant->stock }})</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('admin.gudang.update', $variant) }}" method="POST" class="inline-flex items-center gap-2 justify-end">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="stock" value="{{ $variant->stock }}" min="0" required class="w-20 text-center rounded-lg border-slate-200 text-xs font-bold p-1.5 bg-slate-50 focus:ring-indigo-500">
                                        <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-[11px] transition shadow-2xs">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 font-medium text-xs">
                                    Tidak ada data stok varian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $variants->links() }}
            </div>
        </div>

    </div>

@endsection
