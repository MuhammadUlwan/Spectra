<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Investment;
use App\Models\Profit;
use App\Models\Announcement;
use App\Models\Withdrawal;
use App\Models\Setting;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil currency dari setting (default USD)
        $currencySetting = Setting::where('key_name', 'currency')->first();
        $currency = $currencySetting->value ?? 'USDT';
        $investorAktif = Investment::where('status', 'active')->distinct('user_id')->count('user_id');
        $totalInvestasi = Investment::where('status', 'active')->sum('amount');
        $totalProfit = Profit::sum('profit_amount');
        $withdrawPending = Withdrawal::where('status', 'pending')->count();

        $stats = [
            'total_users'     => User::count(),
            'investor_aktif'  => $investorAktif,
            'total_investasi' => $totalInvestasi,
            'total_profit'    => $totalProfit,
            'withdraw_pending'=> $withdrawPending,
            'currency'        => $currency,
        ];

        // Announcements
        $announcements = Announcement::where('active', true)
            ->orderBy('order', 'asc')
            ->get()
            ->map(fn($a) => [
                'id'        => $a->id,
                'title'     => $a->title,
                'image_url' => $a->image_url,
            ]);

        return Inertia::render('Admin/Dashboard', [
            'auth'          => ['user' => $user],
            'stats'         => $stats,
            'announcements' => $announcements,
            'logoutUrl'     => route('logout'),
            'profileUrl'    => route('admin.profile'),
        ]);
    }

    /**
     * Contoh fungsi ambil rate konversi USD -> target currency
     */
    private function getExchangeRate($from, $to)
    {
        // Hardcode sementara, nanti bisa pakai tabel exchange_rates atau API
        $rates = [
            'USDT' => 1,
            'IDR' => 15000, // 1 USD = 15.000 IDR
            'EUR' => 0.92,
        ];

        $fromRate = $rates[$from] ?? 1;
        $toRate = $rates[$to] ?? 1;

        return $toRate / $fromRate;
    }
}
