<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Investment;
use App\Models\Profit;
use App\Models\Commission;
use App\Models\Announcement;
use App\Models\Setting;

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


        // Controllers/Investor/DashboardController.php (atau yang setara)

        $settings = Setting::whereIn('key_name', ['url_chatbot','url_tutorial','url_academy'])
                    ->pluck('value','key_name')
                    ->toArray();

        return Inertia::render('Investor/DashboardInvestor', [
            'auth'           => ['user' => $user],
            'walletBalance'  => $walletBalance,
            'depositBalance' => $depositBalance,
            'totalProfit'    => $totalProfit,
            'announcements'  => $announcements,
            'urls' => [
                'academy'  => $settings['url_academy'] ?? '',
                'tutorial' => $settings['url_tutorial'] ?? '',
                'chatbot'  => $settings['url_chatbot'] ?? '',
            ],
            'logoutUrl'      => route('logout'),
            'profileUrl'     => route('profile'),
        ]);
    }

    public function getWalletBalance()
    {
        $user = auth()->user();

        $depositBalance = (float) Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('amount');

        $totalProfit = (float) Profit::where('user_id', $user->id)
            ->where('status', 'paid')
            ->where('withdraw_method', 'wallet')
            ->sum('profit_amount');

        $totalCommissions = (float) Commission::where('user_id', $user->id)
            ->where('status', 'paid')
            ->where('withdraw_method', 'wallet')
            ->sum('amount');

        $walletBalance = round($depositBalance + $totalProfit + $totalCommissions, 2);

        return response()->json([
            'walletBalance' => $walletBalance,
            'depositBalance' => $depositBalance,
            'totalProfit' => $totalProfit,
        ]);
    }

}