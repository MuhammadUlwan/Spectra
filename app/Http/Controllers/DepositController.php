<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    // Halaman daftar paket deposit/investasi
    public function index()
    {
        $user = Auth::user();

        $packages = DB::table('investment_packages')
            ->select('id','name','amount','duration_months','profit_percent','currency')
            ->get()
            ->map(function($p){
                $p->profit_15days = round($p->amount * 3.25 / 100, 2);
                $p->profit_monthly = round($p->amount * 7.5 / 100, 2);
                $p->total_profit = round($p->profit_monthly * $p->duration_months, 2);
                return $p;
            });

        return inertia('Deposit', [
            'user' => $user,
            'packages' => $packages,
            'profileUrl' => route('profile'),
            'logoutUrl' => route('logout')
        ]);
    }

    // Simpan deposit ke database
    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:investment_packages,id',
            'amount' => 'required|numeric|min:1',
            'proof_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $user = Auth::user();

        $package = DB::table('investment_packages')->where('id', $request->package_id)->first();
        if (!$package) {
            return redirect()->back()->with('error', 'Paket tidak ditemukan.');
        }

        // Simpan file bukti transfer dengan nama unik
        $file = $request->file('proof_transfer');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $proofPath = $file->storeAs('public/deposits', $filename);

        // Simpan ke tabel investments
        DB::table('investments')->insert([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'amount' => $request->amount,
            'start_date' => null,
            'end_date' => null,
            'status' => 'pending',
            'proof_transfer' => $proofPath,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('deposit.index')->with('success', 'Deposit berhasil dikirim. Tunggu validasi admin.');
    }
}
