@extends('layouts.admin')

@section('title', 'Edit Landing Page — Admin Sepatu.id')
@section('page_title', 'Kelola Teks Landing Page')

@section('content')

    <div class="max-w-4xl space-y-6">

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Teks &amp; Kata-kata Landing Page</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Edit teks hero banner, announcement bar, dan fitur keunggulan yang tampil di halaman depan katalog publik.</p>
        </div>

        <form action="{{ route('admin.settings.landing.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Hero Banner -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-indigo-600 border-b border-slate-100 pb-3">
                    1. Hero Section (Banner Utama Top)
                </h3>

                <div class="space-y-4 text-xs font-semibold">
                    <div>
                        <label class="block uppercase text-slate-600 mb-1">Badge Teks (di atas judul):</label>
                        <input type="text" name="settings[hero_badge]" value="{{ old('settings.hero_badge', $settings['hero_badge']->value ?? 'New Concept Collection 2026') }}" class="w-full rounded-xl border-slate-300 text-xs p-3 font-medium focus:ring-indigo-600 focus:border-indigo-600">
                    </div>

                    <div>
                        <label class="block uppercase text-slate-600 mb-1">Judul Utama Hero:</label>
                        <input type="text" name="settings[hero_title]" value="{{ old('settings.hero_title', $settings['hero_title']->value ?? 'sepatu.id') }}" class="w-full rounded-xl border-slate-300 text-xs p-3 font-medium focus:ring-indigo-600 focus:border-indigo-600">
                    </div>

                    <div>
                        <label class="block uppercase text-slate-600 mb-1">Sub-judul / Deskripsi Hero:</label>
                        <textarea name="settings[hero_subtitle]" rows="3" class="w-full rounded-xl border-slate-300 text-xs p-3 font-medium focus:ring-indigo-600 focus:border-indigo-600">{{ old('settings.hero_subtitle', $settings['hero_subtitle']->value ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Announcement Bar -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-indigo-600 border-b border-slate-100 pb-3">
                    2. Announcement Bar (Top Banner Header)
                </h3>

                <div class="space-y-4 text-xs font-semibold">
                    <div>
                        <label class="block uppercase text-slate-600 mb-1">Teks Banner Tengah (Promo / Gratis Ongkir):</label>
                        <input type="text" name="settings[announcement_bar]" value="{{ old('settings.announcement_bar', $settings['announcement_bar']->value ?? '') }}" class="w-full rounded-xl border-slate-300 text-xs p-3 font-medium focus:ring-indigo-600 focus:border-indigo-600">
                    </div>

                    <div>
                        <label class="block uppercase text-slate-600 mb-1">Teks Kiri (Garansi):</label>
                        <input type="text" name="settings[announcement_left]" value="{{ old('settings.announcement_left', $settings['announcement_left']->value ?? '') }}" class="w-full rounded-xl border-slate-300 text-xs p-3 font-medium focus:ring-indigo-600 focus:border-indigo-600">
                    </div>

                    <div>
                        <label class="block uppercase text-slate-600 mb-1">Teks Kanan (Kontak CS):</label>
                        <input type="text" name="settings[announcement_right]" value="{{ old('settings.announcement_right', $settings['announcement_right']->value ?? '') }}" class="w-full rounded-xl border-slate-300 text-xs p-3 font-medium focus:ring-indigo-600 focus:border-indigo-600">
                    </div>
                </div>
            </div>

            <!-- Section 3: Feature Cards -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-indigo-600 border-b border-slate-100 pb-3">
                    3. Best Seller / Keunggulan Brand (4 Card Below)
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-semibold">
                    <div class="p-4 bg-slate-50 rounded-xl space-y-2 border border-slate-200/60">
                        <label class="block uppercase text-slate-700 font-extrabold">Fitur 1 Judul:</label>
                        <input type="text" name="settings[feature_1_title]" value="{{ old('settings.feature_1_title', $settings['feature_1_title']->value ?? '') }}" class="w-full rounded-lg border-slate-300 text-xs p-2.5">
                        <label class="block uppercase text-slate-700 font-extrabold pt-1">Fitur 1 Deskripsi:</label>
                        <input type="text" name="settings[feature_1_desc]" value="{{ old('settings.feature_1_desc', $settings['feature_1_desc']->value ?? '') }}" class="w-full rounded-lg border-slate-300 text-xs p-2.5">
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl space-y-2 border border-slate-200/60">
                        <label class="block uppercase text-slate-700 font-extrabold">Fitur 2 Judul:</label>
                        <input type="text" name="settings[feature_2_title]" value="{{ old('settings.feature_2_title', $settings['feature_2_title']->value ?? '') }}" class="w-full rounded-lg border-slate-300 text-xs p-2.5">
                        <label class="block uppercase text-slate-700 font-extrabold pt-1">Fitur 2 Deskripsi:</label>
                        <input type="text" name="settings[feature_2_desc]" value="{{ old('settings.feature_2_desc', $settings['feature_2_desc']->value ?? '') }}" class="w-full rounded-lg border-slate-300 text-xs p-2.5">
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl space-y-2 border border-slate-200/60">
                        <label class="block uppercase text-slate-700 font-extrabold">Fitur 3 Judul:</label>
                        <input type="text" name="settings[feature_3_title]" value="{{ old('settings.feature_3_title', $settings['feature_3_title']->value ?? '') }}" class="w-full rounded-lg border-slate-300 text-xs p-2.5">
                        <label class="block uppercase text-slate-700 font-extrabold pt-1">Fitur 3 Deskripsi:</label>
                        <input type="text" name="settings[feature_3_desc]" value="{{ old('settings.feature_3_desc', $settings['feature_3_desc']->value ?? '') }}" class="w-full rounded-lg border-slate-300 text-xs p-2.5">
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl space-y-2 border border-slate-200/60">
                        <label class="block uppercase text-slate-700 font-extrabold">Fitur 4 Judul:</label>
                        <input type="text" name="settings[feature_4_title]" value="{{ old('settings.feature_4_title', $settings['feature_4_title']->value ?? '') }}" class="w-full rounded-lg border-slate-300 text-xs p-2.5">
                        <label class="block uppercase text-slate-700 font-extrabold pt-1">Fitur 4 Deskripsi:</label>
                        <input type="text" name="settings[feature_4_desc]" value="{{ old('settings.feature_4_desc', $settings['feature_4_desc']->value ?? '') }}" class="w-full rounded-lg border-slate-300 text-xs p-2.5">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-widest transition shadow-md">
                    Simpan Perubahan Landing Page
                </button>
            </div>

        </form>
    </div>

@endsection
