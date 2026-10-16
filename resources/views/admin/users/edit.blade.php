@extends('layouts.admin')

@section('title', 'Edit Hak Akses Pengguna — Admin Sepatu.id')
@section('page_title', 'Edit Hak Akses Pengguna')

@section('content')

    <div class="max-w-md space-y-6">

        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Kembali ke Pengguna &amp; Hak Akses</span>
        </a>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-5">
            @csrf
            @method('PUT')

            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">
                        Atur Hak Akses Akun
                    </h3>
                    <p class="text-[11px] text-slate-500 font-medium">Sesuai kebijakan keamanan, data identitas pengguna terkunci.</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
            </div>

            <!-- Nama Lengkap (TERKUNCI) -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-semibold text-slate-700">Nama Lengkap</label>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <span>Terkunci</span>
                    </span>
                </div>
                <div class="relative">
                    <input type="text" value="{{ $user->name }}" readonly disabled class="w-full rounded-xl border-slate-200 text-xs font-bold p-3 bg-slate-100 text-slate-600 cursor-not-allowed select-none">
                </div>
            </div>

            <!-- Alamat Email (TERKUNCI) -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-semibold text-slate-700">Alamat Email</label>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <span>Terkunci</span>
                    </span>
                </div>
                <div class="relative">
                    <input type="email" value="{{ $user->email }}" readonly disabled class="w-full rounded-xl border-slate-200 text-xs font-bold p-3 bg-slate-100 text-slate-600 cursor-not-allowed select-none">
                </div>
            </div>

            <!-- Role Hak Akses (DAPAT DIUBAH) -->
            <div class="pt-2 border-t border-slate-100">
                <label class="block text-xs font-bold text-indigo-950 mb-1 uppercase tracking-wider">
                    Role Hak Akses <span class="text-rose-500">*</span>
                </label>
                <select name="role" required class="w-full rounded-xl border-indigo-200 text-xs font-bold uppercase p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-indigo-50/50 text-indigo-950">
                    <option value="admin" {{ old('role', $user->role) == 'admin' || $user->role == 'super_admin' ? 'selected' : '' }}>Admin (Akses Panel Admin / Control Center)</option>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User (Pelanggan Storefront)</option>
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Admin memiliki akses ke dashboard pengelolaan sistem. User hanya memiliki akses belanja.</p>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-2xs mt-2">
                Simpan Perubahan Role
            </button>
        </form>

    </div>

@endsection
