<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Investment;
use App\Models\Profit;
use App\Models\AffiliateCommission;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ===== Hitung deposit aktif =====
        $depositBalance = Investment::where('user_id', $user->id)
            ->whereIn('status', ['active', 'success', 'completed', 'paid'])
            ->sum('amount');

        // Ambil deposit pertama user (kalau ada)
        $firstDeposit = Investment::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->value('amount') ?? 0;

        // ===== Total Profit paid =====
        $totalProfit = Profit::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('profit_amount');

        // ===== Extra Bonus =====
        $extraBonus = AffiliateCommission::where('user_id', $user->id)
            ->where('type', 'bonus')
            ->where('status', 'paid')
            ->sum('amount');

        // ===== Sponsor Fee =====
        $sponsorFee = AffiliateCommission::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereIn('type', ['sponsor', 'sponsor_fee', 'affiliate_bonus'])
            ->sum('amount');

        // ===== Total Wallet =====
        $walletBalance = $depositBalance + $totalProfit + $extraBonus + $sponsorFee;

        // ===== Ambil pengumuman aktif =====
        $announcements = Announcement::where('active', 1)
            ->orderBy('order')
            ->get()
            ->map(fn($a) => [
                'id'        => $a->id,
                'title'     => $a->title,
                'image_url' => $a->image_url,
            ]);

        return Inertia::render('Investor/DashboardInvestor', [
            'auth'           => ['user' => $user],
            'walletBalance'  => $walletBalance,
            'depositBalance' => $depositBalance,
            'totalProfit'    => $totalProfit,
            'extraBonus'     => $extraBonus,
            'sponsorFee'     => $sponsorFee,
            'announcements'  => $announcements,
            'logoutUrl'      => route('logout'),
            'profileUrl'     => route('profile'),
            'pendingDeposit' => $firstDeposit < 10, // true jika belum deposit ≥ 10
            'firstDeposit'   => $firstDeposit,
        ]);
    }   

    // API untuk refresh wallet balance via axios
    public function getWalletBalance()
    {
        $user = Auth::user();

        $depositBalance = Investment::where('user_id', $user->id)
            ->whereIn('status', ['active', 'success', 'completed', 'paid'])
            ->sum('amount');

        $totalProfit = Profit::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('profit_amount');

        $extraBonus = AffiliateCommission::where('user_id', $user->id)
            ->where('type', 'bonus')
            ->where('status', 'paid')
            ->sum('amount');

        $sponsorFee = AffiliateCommission::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereIn('type', ['sponsor', 'sponsor_fee', 'affiliate_bonus'])
            ->sum('amount');

        $walletBalance = $depositBalance + $totalProfit + $extraBonus + $sponsorFee;

        return response()->json([
            'walletBalance'  => $walletBalance,
            'depositBalance' => $depositBalance,
            'totalProfit'    => $totalProfit,
            'extraBonus'     => $extraBonus,
            'sponsorFee'     => $sponsorFee,
        ]);
    }
}
