<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Investment;
use App\Models\Profit;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Wallet balance: ambil dari kolom user
        $walletBalance = $user->wallet ?? 0;

        // Deposit balance: jumlah total investasi aktif
        $depositBalance = Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('amount');

        // Total profit: jumlah dari tabel profits
        $totalProfit = Profit::where('user_id', $user->id)->sum('amount');

        // Announcement: ambil dari settings
        $announcement = Setting::where('key_name', 'dashboard_announcement')->value('value') 
            ?? 'Belum ada pengumuman.';

        return Inertia::render('Dashboard', [
            'auth'           => ['user' => $user],
            'walletBalance'  => $walletBalance,
            'depositBalance' => $depositBalance,
            'totalProfit'    => $totalProfit,
            'announcement'   => $announcement,
            'logoutUrl'      => route('logout'),
            'profileUrl'     => route('profile'),
        ]);
    }
}
