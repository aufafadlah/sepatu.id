<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    /**
     * Tampilkan form edit teks landing page.
     */
    public function landing()
    {
        $settings = SiteSetting::all()->keyBy('key');

        return view('admin.settings.landing', compact('settings'));
    }

    /**
     * Simpan perubahan teks landing page.
     */
    public function updateLanding(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:1000',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            SiteSetting::set($key, $value);
        }

        auth()->user()->logActivity('update_landing_text', 'Mengubah teks landing page');

        return back()->with('success', 'Teks landing page berhasil diperbarui!');
    }
}
