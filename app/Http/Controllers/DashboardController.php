<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Investment;
use App\Models\Profit;
use App\Models\Commission;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Deposit aktif
        $depositBalance = (float) Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('amount');
        $depositBalance = round($depositBalance, 2);

        // Total profit paid via wallet
        $totalProfit = (float) Profit::where('user_id', $user->id)
            ->where('status', 'paid')
            ->where('withdraw_method', 'wallet')
            ->sum('profit_amount');
        $totalProfit = round($totalProfit, 2);

        // Total commission paid via wallet
        $totalCommissions = (float) Commission::where('user_id', $user->id)
            ->where('status', 'paid')
            ->where('withdraw_method', 'wallet')
            ->sum('amount');
        $totalCommissions = round($totalCommissions, 2);

        // Total wallet = deposit + profit + commissions
        $walletBalance = round($depositBalance + $totalProfit + $totalCommissions, 2);

        // Ambil pengumuman aktif
        $announcements = Announcement::where('active', 1)
            ->orderBy('order')
            ->get();

        return Inertia::render('DashboardInvestor', [
            'auth'             => ['user' => $user],
            'depositBalance'   => $depositBalance,
            'totalProfit'      => $totalProfit,
            'totalCommissions' => $totalCommissions,
            'walletBalance'    => $walletBalance,
            'announcements'    => $announcements,
            'profileUrl'       => route('profile'),
            'logoutUrl'        => route('logout'),
            'user_preferences' => $user->preferences,
        ]);
    }
}
