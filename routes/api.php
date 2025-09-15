<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

// ========================
// Validasi Kode Referral
// ========================
// Endpoint: /api/check-referral?kode=KODE123
Route::get('/check-referral', function (Request $request) {
    $kode = $request->query('kode');
    $valid = false;

    if ($kode) {
        // ✅ Perubahan: sekarang cek berdasarkan referral_code, bukan username
        $valid = User::where('referral_code', $kode)->exists();
    }

    return response()->json([
        'kode' => $kode,
        'valid' => $valid,
    ]);
});
