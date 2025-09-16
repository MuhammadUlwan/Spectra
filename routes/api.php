<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// ========================
// Validasi Kode Referral
// ========================
Route::get('/check-referral', function (Request $request) {
    $kode = $request->query('kode');
    $valid = false;

    if ($kode) {
        $valid = User::where('referral_code', $kode)->exists();
    }

    return response()->json([
        'kode' => $kode,
        'valid' => $valid,
    ]);
});

// ========================
// Summary Referral per Level
// ========================
Route::middleware('auth:sanctum')->get('/referrals-summary', function () {
    $user = Auth::user();

    // Fungsi rekursif untuk menghitung referral per level
    function referralCount(User $user, int $level): int
    {
        if ($level === 1) {
            return $user->referrals()->count();
        }

        $count = 0;
        foreach ($user->referrals as $ref) {
            $count += referralCount($ref, $level - 1);
        }
        return $count;
    }

    $maxLevel = 5; // Bisa diubah sesuai kebutuhan
    $summary = [];
    for ($i = 1; $i <= $maxLevel; $i++) {
        $summary[$i] = referralCount($user, $i);
    }

    return response()->json($summary);
});