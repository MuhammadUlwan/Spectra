<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // siapkan data softcode
        return Inertia::render('Investor/Profile', [
            'auth' => [
                'user' => $user,
            ],
            'billing'       => [], // nanti ambil dari tabel investasi/deposit
            'referral'      => [
                'qr'  => null, // bisa auto-generate QR dari referral code
                'code' => $user->referral_code ?? '',
            ],
            'notifications' => [
                ['name' => 'email', 'label' => 'Email Notification', 'icon' => 'fas fa-envelope', 'enabled' => true],
                ['name' => 'telegram', 'label' => 'Telegram Notification', 'icon' => 'fas fa-paper-plane', 'enabled' => false],
            ],
            'preferences'   => [
                'theme'    => 'light',
                'language' => 'id',
            ],
        ]);
    }

    public function updateNotifications(Request $request)
    {
        // Simpan preferensi notifikasi user
        // (sementara dummy, nanti pakai tabel user_settings/notifications)
        return back()->with('success', 'Pengaturan notifikasi berhasil disimpan.');
    }

    public function updatePreferences(Request $request)
    {
        // Simpan preferensi user
        return back()->with('success', 'Preferensi berhasil disimpan.');
    }
}