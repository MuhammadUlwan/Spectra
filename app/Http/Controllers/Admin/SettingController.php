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
                'chatbot_url'  => $settings['chatbot_url'] ?? '',
                // Profit & fee settings
                'profit_percent'          => $settings['profit_percent'] ?? 7.5,
                'sponsor_fee_direct'      => $settings['sponsor_fee_direct'] ?? 7.5,
                'sponsor_fee_indirect'    => $settings['sponsor_fee_indirect'] ?? 2.5,
                'profit_sharing_level1'   => $settings['profit_sharing_level1'] ?? 1.25,
                'profit_sharing_level2'   => $settings['profit_sharing_level2'] ?? 0.75,
                'profit_sharing_level3'   => $settings['profit_sharing_level3'] ?? 0.5,
                'bonus_target_omset'      => $settings['bonus_target_omset'] ?? 10000,
                'bonus_profit_extra'      => $settings['bonus_profit_extra'] ?? 1.25,
                'profit_percent_15days'   => $settings['profit_percent_15days'] ?? 3.5,
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
        // validasi profit settings
            'profit_percent'            => 'nullable|numeric|min:0',
            'sponsor_fee_direct'        => 'nullable|numeric|min:0',
            'sponsor_fee_indirect'      => 'nullable|numeric|min:0',
            'profit_sharing_level1'     => 'nullable|numeric|min:0',
            'profit_sharing_level2'     => 'nullable|numeric|min:0',
            'profit_sharing_level3'     => 'nullable|numeric|min:0',
            'bonus_target_omset'        => 'nullable|numeric|min:0',
            'bonus_profit_extra'        => 'nullable|numeric|min:0',
            'profit_percent_15days'     => 'nullable|numeric|min:0',
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
        // Update URL Settings
        // ========================
        $urlKeys = ['academy_url', 'tutorial_url', 'chatbot_url'];
        foreach ($urlKeys as $key) {
            if ($request->filled($key)) {
                Setting::updateOrCreate(
                    ['key_name' => $key],
                    ['value' => $request->$key]
                );
            }
        }

        // ========================
        // Update Profit & Fee Settings
        // ========================
        $profitKeys = [
            'profit_percent',
            'sponsor_fee_direct',
            'sponsor_fee_indirect',
            'profit_sharing_level1',
            'profit_sharing_level2',
            'profit_sharing_level3',
            'bonus_target_omset',
            'bonus_profit_extra',
            'profit_percent_15days',
        ];

        foreach ($profitKeys as $key) {
            if ($request->filled($key)) {
                Setting::updateOrCreate(
                    ['key_name' => $key],
                    ['value' => $request->$key]
                );
            }
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
