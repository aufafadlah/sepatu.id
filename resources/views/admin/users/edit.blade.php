@extends('layouts.admin')

@section('title', 'Edit Akun — Admin Sepatu.id')
@section('page_title', 'Edit Akun Pengguna')

@section('content')

    <div class="max-w-md space-y-6">

        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Kembali ke Pengguna &amp; Hak Akses</span>
        </a>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-4">
            @csrf
            @method('PUT')

            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                Edit Akun: {{ $user->name }}
            </h3>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Role Hak Akses <span class="text-rose-500">*</span></label>
                <select name="role" required class="w-full rounded-xl border-slate-200 text-xs font-semibold uppercase p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    <option value="admin" {{ old('role', $user->role) == 'admin' || $user->role == 'super_admin' ? 'selected' : '' }}>Admin (Akses Panel Admin)</option>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User (Pelanggan Storefront)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Password Baru (Opsional)</label>
                <input type="password" name="password" placeholder="Biarkan kosong jika tidak diubah..." class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-2xs">
                Perbarui Akun
            </button>
        </form>

    </div>

@endsection
