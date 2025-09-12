<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class InvestmentController extends Controller
{
    // Investor: lihat daftar investasi miliknya
    public function index()
    {
        $investments = Investment::with('package')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $packages = \App\Models\InvestmentPackage::all();

        return Inertia::render('Investments/Index', [
            'investments' => $investments,
            'packages' => $packages,
        ]);
    }

    // Investor: buat investasi baru
    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:investment_packages,id',
            'proof_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = $request->file('proof_transfer')->store('proofs', 'public');

        $package = InvestmentPackage::findOrFail($request->package_id);

        Investment::create([
            'user_id' => Auth::id(),
            'package_id' => $package->id,
            'amount' => $package->amount,
            'proof_transfer' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('investments.index')->with('success', 'Investasi berhasil diajukan, menunggu validasi admin.');
    }

    // Admin: lihat semua investasi pending
    public function adminIndex()
    {
        $investments = Investment::with(['package', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return Inertia::render('Admin/Investments/Index', [
            'investments' => $investments
        ]);
    }

    // Admin: validasi investasi
    public function validateInvestment($id)
    {
        $investment = Investment::findOrFail($id);

        $investment->update([
            'status' => 'active',
            'validated_by' => Auth::id(),
            'validated_at' => now(),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(6),
        ]);

        return redirect()->back()->with('success', 'Investasi berhasil divalidasi.');
    }
}
