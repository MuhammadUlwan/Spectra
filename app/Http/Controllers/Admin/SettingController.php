<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Setting;
use App\Models\UserPreference;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua global settings (key => value)
        $settings = Setting::all()->pluck('value', 'key_name')->toArray();

        // Ambil user preference, fallback jika belum ada
        $userPref = $user->preference ?? [
            'currency'    => 'USDT',   // fixed USDT
            'bank_name'   => '',
            'bank_number' => '',
            'language'    => 'id',
            'theme'       => 'light',
        ];

        return Inertia::render('Admin/Settings', [
            'auth'       => ['user' => $user],
            'userPref'   => $userPref,
            'settings'   => [
                'url_academy'  => $settings['url_academy'] ?? '',
                'url_tutorial' => $settings['url_tutorial'] ?? '',
            ],
            'logoutUrl'  => route('logout'),
            'profileUrl'    => route('admin.profile'),
            'locale'     => app()->getLocale(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'bank_name'    => 'nullable|string',
            'bank_number'  => 'nullable|string',
            'academy_url'  => 'nullable|url',
            'tutorial_url' => 'nullable|url',
            'language'     => 'required|string|in:id,en',
            'theme'        => 'required|string|in:light,dark',
        ]);

        $user = Auth::user();

        // ========================
        // Update user preferences
        // ========================
        $preference = $user->preference()->firstOrNew([]);
        $preference->currency    = 'USDT'; // fixed
        $preference->bank_name   = $request->bank_name;
        $preference->bank_number = $request->bank_number;
        $preference->language    = $request->language;
        $preference->theme       = $request->theme;
        $preference->user_id     = $user->id;
        $preference->save();

        // ========================
        // Update global Settings
        // ========================
        if ($request->filled('academy_url')) {
            Setting::updateOrCreate(
                ['key_name' => 'url_academy'],
                ['value' => $request->academy_url]
            );
        }

        if ($request->filled('tutorial_url')) {
            Setting::updateOrCreate(
                ['key_name' => 'url_tutorial'],
                ['value' => $request->tutorial_url]
            );
        }

        if ($request->filled('chatbot_url')) {
            Setting::updateOrCreate(
                ['key_name' => 'chatbot_url'],
                ['value' => $request->chatbot_url]
            );
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
