<?php

namespace App\Http\Controllers;

use App\Models\InvestmentPackage;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DepositController extends Controller
{
    public function index()
    {
        $packages = InvestmentPackage::all();

        return Inertia::render('Deposit', [
            'auth'     => ['user' => Auth::user()],
            'packages' => $packages,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_ids' => 'required|string',
            'proof'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $packageIds = explode(',', $request->package_ids);

        foreach ($packageIds as $packageId) {
            Investment::create([
                'user_id'        => Auth::id(),
                'package_id'     => $packageId,
                'amount'         => InvestmentPackage::find($packageId)->amount,
                'start_date'     => now(),
                'status'         => 'pending',
                'proof_transfer' => $request->file('proof')->store('proofs', 'public'),
            ]);
        }

        return back()->with('success', 'Deposit berhasil, menunggu validasi admin.');
    }
}
