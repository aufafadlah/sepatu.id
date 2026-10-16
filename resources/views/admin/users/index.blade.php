@extends('layouts.admin')

@section('title', 'Pengguna & Hak Akses — Admin Sepatu.id')
@section('page_title', 'Pengguna & Hak Akses')

@section('content')

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Manajemen Pengguna &amp; Hak Akses</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Sistem 2 Hak Akses: Admin (Akses Control Center) &amp; User (Pelanggan Storefront).</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                <span>Tambah Akun Baru</span>
            </a>
        </div>

        <!-- Users Table (Inspired by Zendenta Staff List Table) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 uppercase font-semibold tracking-wider text-[10px]">
                        <tr>
                            <th class="p-4 w-12 text-center">No.</th>
                            <th class="p-4">Nama Pengguna</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Role Hak Akses</th>
                            <th class="p-4">Terdaftar</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 text-center font-bold text-slate-400">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    {{ $user->name }}
                                </td>
                                <td class="p-4 text-slate-500 font-medium">
                                    {{ $user->email }}
                                </td>
                                <td class="p-4">
                                    @if($user->isAdmin())
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-indigo-50 text-indigo-600">
                                            Admin System
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                            User (Pelanggan)
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-slate-400 font-medium">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition">
                                            Edit
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus akun pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-[11px] transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 font-medium text-xs">
                                    Belum ada data pengguna terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

    </div>

@endsection
