<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CareerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data statistik untuk dashboard konsultan
        $stats = [
            'total_commission' => $user->commissions()->sum('amount'),
            'referrals' => [
                'level1' => $user->referrals()->count(),
                'level2' => $user->referrals()->with('referrals')->get()->pluck('referrals')->flatten()->count()
            ],
            'direct_volume' => $user->referrals()->with('deposits')->get()->pluck('deposits')->flatten()->sum('amount'),
            'bonus_active' => $user->referrals()->with('deposits')->get()->pluck('deposits')->flatten()->sum('amount') >= 10000
        ];
        
        // Riwayat komisi
        $commissionHistory = $user->commissions()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($commission) {
                return [
                    'date' => $commission->created_at->format('d M Y'),
                    'source' => $commission->type === 'sponsor' ? 
                        ('Sponsor Fee Level ' . $commission->level) : 
                        ('Profit Sharing Level ' . $commission->level),
                    'amount' => $commission->amount,
                    'status' => $commission->status
                ];
            });

        return Inertia::render('Career', [
            'auth' => ['user' => $user],
            'stats' => $stats,
            'commissionHistory' => $commissionHistory,
            'referralLink' => url('/register?ref=' . $user->referral_code),
        ]);
    }
}