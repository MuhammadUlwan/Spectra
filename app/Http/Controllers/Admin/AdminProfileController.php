<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    /**
     * Tampilkan halaman profil admin
     */
    public function index()
    {
        $admin = Auth::user(); // admin sudah login

        return Inertia::render('Admin/Profile', [
            'auth' => [
                'user' => $admin,
            ],
            'notifications' => [
                ['name' => 'email', 'label' => 'Email Notification', 'icon' => 'fas fa-envelope', 'enabled' => true],
                ['name' => 'telegram', 'label' => 'Telegram Notification', 'icon' => 'fas fa-paper-plane', 'enabled' => false],
            ],
            'preferences' => [
                'theme'    => 'light',
                'language' => 'id',
            ],
            'profileUrl' => route('admin.profile'),
            'logoutUrl'  => route('logout'),
        ]);
    }

    /**
     * Simpan preferensi notifikasi admin
     */
    public function updateNotifications(Request $request)
    {
        // Sementara dummy, nanti bisa pakai tabel user_settings
        return back()->with('success', 'Pengaturan notifikasi berhasil disimpan.');
    }

    /**
     * Simpan preferensi admin (tema / bahasa)
     */
    public function updatePreferences(Request $request)
    {
        // Sementara dummy
        return back()->with('success', 'Preferensi berhasil disimpan.');
    }
}