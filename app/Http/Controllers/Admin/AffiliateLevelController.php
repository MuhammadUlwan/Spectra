<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AffiliateLevel;
use Inertia\Inertia;

class AffiliateLevelController extends Controller
{
    // Tampilkan halaman utama (Inertia)
    public function index()
    {
        return Inertia::render('Admin/AffiliateLevels');
    }

    // Endpoint JSON untuk fetch semua level (SPA-friendly)
    public function json()
    {
        $levels = AffiliateLevel::orderBy('level')->get();
        return response()->json($levels);
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|integer',
            'type' => 'required|in:sponsor_fee,profit_sharing',
            'percent' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $level = AffiliateLevel::create($validated);

        return response()->json([
            'message' => 'Level berhasil dibuat',
            'level' => $level
        ]);
    }

    // Update data
    public function update(Request $request, AffiliateLevel $affiliateLevel)
    {
        $validated = $request->validate([
            'level' => 'required|integer',
            'type' => 'required|in:sponsor_fee,profit_sharing',
            'percent' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $affiliateLevel->update($validated);

        return response()->json([
            'message' => 'Level berhasil diupdate',
            'level' => $affiliateLevel
        ]);
    }

    // Hapus data
    public function destroy(AffiliateLevel $affiliateLevel)
    {
        $affiliateLevel->delete();

        return response()->json([
            'message' => 'Level berhasil dihapus'
        ]);
    }
}
