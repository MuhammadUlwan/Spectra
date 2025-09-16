<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Investment; // <-- ganti Deposit jadi Investment

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Billing / Investment
        $billing = Investment::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($d) {
                        return [
                            'id'            => $d->id,
                            'description'   => $d->description ?? 'Investment / Paket',
                            'amount'        => $d->amount,
                            'status'        => $d->status ?? 'pending',
                            'created_at'    => $d->created_at,
                            'wallet_address'=> $d->wallet_address ?? null,
                            'qr_url'        => $d->qr_url ?? null,
                        ];
                    });

        // Referral
        $referral = [
            'qr'   => null, // bisa generate QR dari referral code jika ada
            'code' => $user->referral_code ?? '',
        ];

        // Notifications (dummy)
        $notifications = [
            ['name' => 'email', 'label' => 'Email Notification', 'icon' => 'fas fa-envelope', 'enabled' => true],
            ['name' => 'telegram', 'label' => 'Telegram Notification', 'icon' => 'fas fa-paper-plane', 'enabled' => false],
        ];

        // Preferences (dummy)
        $preferences = [
            'theme'    => 'light',
            'language' => 'id',
        ];

        return Inertia::render('Investor/Profile', [
            'auth'          => ['user' => $user],
            'billing'       => $billing,
            'referral'      => $referral,
            'notifications' => $notifications,
            'preferences'   => $preferences,
        ]);
    }

    public function updateNotifications(Request $request)
    {
        // Simpan preferensi notifikasi user (sementara dummy)
        return back()->with('success', 'Pengaturan notifikasi berhasil disimpan.');
    }

    public function updatePreferences(Request $request)
    {
        // Simpan preferensi user (sementara dummy)
        return back()->with('success', 'Preferensi berhasil disimpan.');
    }
}