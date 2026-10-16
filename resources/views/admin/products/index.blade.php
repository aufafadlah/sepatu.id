@extends('layouts.admin')

@section('title', 'Katalog Produk — Admin Sepatu.id')
@section('page_title', 'Katalog Produk')

@section('content')

    <div class="space-y-6" x-data="{
        deleteModal: false,
        deleteId: null,
        deleteName: '',
        isDeleting: false,
        openDeleteModal(id, name) {
            this.deleteId = id;
            this.deleteName = name;
            this.deleteModal = true;
        },
        async confirmDelete() {
            if (!this.deleteId || this.isDeleting) return;
            this.isDeleting = true;

            const csrfToken = document.querySelector('meta[name=\'csrf-token\']').getAttribute('content');
            try {
                const response = await fetch(`/admin/produk/${this.deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.deleteModal = false;
                    const row = document.getElementById(`product-row-${this.deleteId}`);
                    if (row) {
                        row.classList.add('opacity-0', 'scale-95', 'transition-all', 'duration-300');
                        setTimeout(() => row.remove(), 300);
                    }
                    if (window.showToast) {
                        window.showToast(data.message || 'Produk berhasil dihapus!', 'success');
                    }
                } else {
                    this.deleteModal = false;
                    if (window.showToast) {
                        window.showToast(data.message || 'Gagal menghapus produk.', 'error');
                    }
                }
            } catch (err) {
                this.deleteModal = false;
                if (window.showToast) {
                    window.showToast('Terjadi kesalahan jaringan saat menghapus produk.', 'error');
                }
            } finally {
                this.isDeleting = false;
            }
        }
    }">

        <!-- Top Header & Add Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Daftar Produk Sepatu</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola katalog produk, harga diskon, varian ukuran, dan foto sepatu.</p>
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
                       class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">

                <select name="category_id" onchange="this.form.submit()" class="px-7 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition">
                    Filter
                </button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('admin.produk.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                        Reset
                    </a>
                @endif
            </form>
            <div class="text-xs font-semibold text-slate-500">
                Total: <strong class="text-slate-900">{{ $products->total() }}</strong> produk
            </div>
        </div>

        <!-- Table with No. column and AJAX Delete -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 uppercase font-semibold tracking-wider text-[10px]">
                        <tr>
                            <th class="p-4 w-12 text-center">No.</th>
                            <th class="p-4">Foto</th>
                            <th class="p-4">Nama Produk</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Harga &amp; Diskon</th>
                            <th class="p-4">Total Stok</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse($products as $product)
                            <tr id="product-row-{{ $product->id }}" class="hover:bg-slate-50/80 transition">
                                <td class="p-4 text-center font-bold text-slate-400">
                                    {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                                </td>
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
                                <td class="p-4">
                                    @if($product->hasDiscount())
                                        <div class="space-y-0.5">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-slate-900">
                                                    Rp {{ number_format($product->finalPrice(), 0, ',', '.') }}
                                                </span>
                                                <span class="px-1.5 py-0.5 rounded bg-rose-50 border border-rose-200 text-rose-600 font-bold text-[9px]">
                                                    -{{ $product->discountPercent() }}%
                                                </span>
                                            </div>
                                            <span class="text-[10px] text-slate-400 line-through block">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="font-bold text-slate-900">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    @endif
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
                                        <button type="button"
                                                @click="openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                                class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-[11px] transition">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400 font-medium text-xs">
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

        <!-- Custom Alpine.js Delete Confirmation Modal (Tanpa Full Reload) -->
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" @click.self="deleteModal = false">
            <div x-show="deleteModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 text-center space-y-4">
                
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h3 class="text-base font-bold text-slate-900">
                        Hapus Produk Ini?
                    </h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                        Apakah Anda yakin ingin menghapus produk <span class="font-bold text-slate-800" x-text="deleteName"></span>? Tindakan ini akan menghapus data dan gambar secara permanen jika belum ada riwayat pesanan.
                    </p>
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <button type="button" @click="deleteModal = false" :disabled="isDeleting" class="w-1/2 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="button" @click="confirmDelete()" :disabled="isDeleting" class="w-1/2 py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition shadow-sm flex items-center justify-center gap-2">
                        <svg x-show="isDeleting" class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isDeleting ? 'Menghapus...' : 'Ya, Hapus'"></span>
                    </button>
                </div>

            </div>
        </div>

    </div>

@endsection
