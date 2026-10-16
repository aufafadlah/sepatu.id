@extends('layouts.admin')

@section('title', 'Kategori Sepatu — Admin Sepatu.id')
@section('page_title', 'Kategori Sepatu')

@section('content')

    <div class="space-y-6" x-data="{
        deleteModal: false,
        deleteUrl: '',
        categoryName: '',
        isDeleting: false,
        confirmDelete(url, name) {
            this.deleteUrl = url;
            this.categoryName = name;
            this.deleteModal = true;
        }
    }">

        <!-- Top Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div class="flex items-center gap-4">

                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Kategori Sepatu</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola kategori produk sepatu seperti Running, Casual, Basketball, Lifestyle, dan Training.</p>
                </div>
            </div>
            <a href="{{ route('admin.produk.kategori.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-xs hover:shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Kategori</span>
            </a>
        </div>

        <!-- Search Bar & Statistics -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('admin.produk.kategori.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-80">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori sepatu..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.produk.kategori.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        <span>Reset</span>
                    </a>
                @endif
            </form>

            <div class="flex items-center gap-2 self-start sm:self-center shrink-0">
                <span class="text-xs font-semibold text-slate-500">
                    Total: <strong class="text-slate-900">{{ $totalCategories }}</strong> kategori
                </span>
            </div>
        </div>

        <!-- Category Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                        <tr>
                            <th class="p-4 w-12 text-center">No.</th>
                            <th class="p-4 w-16">ID</th>
                            <th class="p-4">Nama Kategori</th>
                            <th class="p-4">Jumlah Produk Terkait</th>
                            <th class="p-4">Katalog Produk</th>
                            <th class="p-4 text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 text-center font-bold text-slate-400">
                                    {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                                </td>
                                <td class="p-4 text-slate-400 font-bold">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[11px] font-mono">
                                        #{{ $category->id }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100/60 text-indigo-600 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h7M13 11h7M13 15h7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $category->name }}</p>
                                            <p class="text-[11px] text-slate-400 font-normal">Dibuat {{ $category->created_at ? $category->created_at->format('d M Y') : '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-[11px] font-bold text-slate-700 border border-slate-200/60">
                                        <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m-8-10l-8-4m8 4v10l8 4" />
                                        </svg>
                                        <span>{{ $category->products_count }} produk</span>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <a href="{{ route('admin.produk.index', ['category_id' => $category->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition">
                                        <span>Lihat Produk</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.produk.kategori.edit', $category) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </a>
                                        <button type="button" @click="confirmDelete('{{ route('admin.produk.kategori.destroy', $category) }}', '{{ addslashes($category->name) }}')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[11px] transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h7M13 11h7M13 15h7" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700">
                                            @if(request('search'))
                                                Tidak ditemukan kategori dengan kata kunci "{{ request('search') }}"
                                            @else
                                                Belum ada kategori produk sepatu.
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-400 max-w-sm">
                                            @if(request('search'))
                                                Coba periksa kembali ejaan atau reset pencarian Anda.
                                            @else
                                                Mulai buat kategori baru untuk mengelompokkan koleksi sepatu Anda.
                                            @endif
                                        </p>
                                        @if(request('search'))
                                            <a href="{{ route('admin.produk.kategori.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                                Reset Pencarian
                                            </a>
                                        @else
                                            <a href="{{ route('admin.produk.kategori.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition">
                                                Tambah Kategori Sekarang
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

        <!-- Custom Delete Modal with Loading State -->
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 border border-slate-200" @click.outside="if(!isDeleting) deleteModal = false">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Kategori</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>

                <p class="text-xs text-slate-700 leading-relaxed">
                    Apakah Anda yakin ingin menghapus kategori <strong class="text-slate-900" x-text="categoryName"></strong>?
                </p>

                <form :action="deleteUrl" method="POST" @submit="isDeleting = true">
                    @csrf
                    @method('DELETE')

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="deleteModal = false" :disabled="isDeleting" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition disabled:opacity-50">
                            Batal
                        </button>
                        <button type="submit" :disabled="isDeleting" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-2xs disabled:opacity-50">
                            <svg x-show="isDeleting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="isDeleting ? 'Menghapus...' : 'Ya, Hapus Kategori'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
