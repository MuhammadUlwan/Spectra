<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminReferralController extends Controller
{
    public function index()
    {
        // Ambil semua user dengan sponsor
        $referrals = User::with('sponsor')->get();

        $referrals->transform(function ($user) {
            $level = 1;
            if ($user->sponsor && $user->sponsor->sponsor) {
                $level = 2;
            }
            if ($user->sponsor && $user->sponsor->sponsor && $user->sponsor->sponsor->sponsor) {
                $level = 3;
            }

            return [
                'id'            => $user->id,
                'name'          => $user->name,
                'sponsor'       => $user->sponsor?->referral_code,
                'referral_code' => $user->referral_code,
                'level'         => $level,
                'created_at'    => $user->created_at->format('Y-m-d H:i:s'),
            ];
        });

        // Hitung statistik
        $level1 = $referrals->where('level', 1)->count();
        $level2 = $referrals->where('level', 2)->count();
        $level3 = $referrals->where('level', 3)->count();

        $stats = [
            'level1' => $level1,
            'level2' => $level2,
            'level3' => $level3,
            'total'  => $level1 + $level2 + $level3,
        ];

        return inertia('Admin/Referrals', [
            'referrals' => $referrals,
            'stats'     => $stats,
        ]);
    }
}
