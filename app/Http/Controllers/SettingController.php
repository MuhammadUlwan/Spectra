<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\UserPreference;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $userPref = $user->preference ?? [
            'currency'    => 'USDT',
            'bank_name'   => '',
            'bank_number' => '',
            'language'    => 'id',
            'theme'       => 'light',
        ];

        return Inertia::render('Investor/Settings', [
            'auth'       => ['user' => $user],
            'userPref'   => $userPref,
            'settings'   => [
                'url_academy'  => '',
                'url_tutorial' => '',
                'chatbot_url'  => '',
            ],
            'profileUrl' => route('profile'),
            'logoutUrl'  => route('logout'),
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
            'chatbot_url'  => 'nullable|url',
            'language'     => 'required|string|in:id,en',
            'theme'        => 'required|string|in:light,dark',
        ]);

        $user = Auth::user();

        $preference = $user->preference;
        if (!$preference) {
            $preference = new \App\Models\UserPreference();
            $preference->user_id = $user->id;
        }

        $preference->currency    = 'USDT';
        $preference->bank_name   = $request->bank_name;
        $preference->bank_number = $request->bank_number;
        $preference->language    = $request->language;
        $preference->theme       = $request->theme;
        $preference->save();

        return redirect()->route('investor.settings')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
