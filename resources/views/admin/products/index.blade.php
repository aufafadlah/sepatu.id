@extends('layouts.admin')

@section('title', 'Katalog Produk — Admin Sepatu.id')
@section('page_title', 'Katalog Produk')

@section('content')

    <div class="space-y-6">

        <!-- Top Header & Add Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Daftar Produk Sepatu</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola katalog produk, harga, varian ukuran, dan foto sepatu.</p>
            </div>
            <a href="{{ route('admin.produk.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                <span>Tambah Produk Baru</span>
            </a>
        </div>

        <!-- Filter Controls -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('admin.produk.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..."
                       class="px-4  py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">

                <select name="category_id" onchange="this.form.submit()" class="px-7 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Table (Inspired by Zendenta Treatments Table) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 uppercase font-semibold tracking-wider text-[10px]">
                        <tr>
                            <th class="p-4">Foto</th>
                            <th class="p-4">Nama Produk</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Harga</th>
                            <th class="p-4">Total Stok</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse($products as $product)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center p-1">
                                        @if($product->image)
                                            <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}" class="max-h-full max-w-full object-contain">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=200&q=80" class="max-h-full max-w-full object-contain">
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    <a href="{{ route('admin.produk.show', $product) }}" class="hover:text-indigo-600 transition">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-semibold">
                                        {{ $product->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="p-4">
                                    @php $stock = $product->totalStock(); @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $stock < 5 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                                        {{ $stock }} pasang
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.produk.edit', $product) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.produk.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-[11px] transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400 font-medium text-xs">
                                    Belum ada produk sepatu dalam katalog.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        </div>

    </div>

@endsection
