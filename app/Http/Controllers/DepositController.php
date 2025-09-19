<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Models\Investment;

class DepositController extends Controller
{
    /**
     * Halaman daftar paket deposit/investasi
     */
    public function index()
    {
        $settings = Setting::pluck('value', 'key_name')->toArray();
        $user = Auth::user();

        $packages = DB::table('investment_packages')
            ->select('id', 'name', 'amount', 'duration_months', 'profit_percent', 'currency')
            ->get()
            ->map(function ($p) use ($settings) {
                $p->profit_15days = round($p->amount * (floatval($settings['profit_percent_15days'] ?? 3.25) / 100), 2);
                $p->profit_monthly = round($p->amount * (floatval($settings['profit_percent'] ?? 7.5) / 100), 2);
                $p->total_profit = round($p->profit_monthly * $p->duration_months, 2);
                return $p;
            });

        // 👉 Ambil data bank dari user_preferences milik admin
        $bankInfo = DB::table('user_preferences')
            ->join('users', 'users.id', '=', 'user_preferences.user_id')
            ->where('users.role', 'admin')
            ->select(
                'user_preferences.bank_name',
                'user_preferences.bank_number',
                'user_preferences.bank_qr'
            )
            ->first();

        return inertia('Investor/Deposit', [
            'user'       => $user,
            'packages'   => $packages,
            'settings'   => $settings,
            'bankInfo'   => $bankInfo,
            'profileUrl' => route('profile'),
            'logoutUrl'  => route('logout')
        ]);
    }

    /**
     * Simpan deposit paket (investasi minimal 300 USDT)
     */
    public function store(Request $request)
    {
        $request->validate([
            'package_id'     => 'required|exists:investment_packages,id',
            'transfer_date'  => 'required|date',
            'transfer_file'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();

        // Simpan file bukti transfer
        $file = $request->file('transfer_file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/deposits');
        if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);
        $file->move($destinationPath, $filename);
        $proofPath = 'deposits/' . $filename;

        $package = DB::table('investment_packages')->where('id', $request->package_id)->first();

        DB::table('investments')->insert([
            'user_id'        => $user->id,
            'package_id'     => $package->id,
            'amount'         => $package->amount,
            'start_date'     => null,
            'end_date'       => null,
            'status'         => 'pending',
            'proof_transfer' => $proofPath,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Deposit berhasil! Menunggu konfirmasi admin.');
    }

    /**
     * Upload bukti deposit awal 10 USDT
     */
    public function uploadProof(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();

        // Simpan file bukti transfer
        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/deposits');
        if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);
        $file->move($destinationPath, $filename);
        $proofPath = 'deposits/' . $filename;

        // Simpan ke tabel investments (tanpa package_id)
        $deposit = Investment::create([
            'user_id'        => $user->id,
            'package_id'     => null, // tidak ada paket untuk deposit awal
            'amount'         => 10,
            'start_date'     => null,
            'end_date'       => null,
            'status'         => 'pending',
            'proof_transfer' => $proofPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Deposit awal berhasil diajukan, menunggu verifikasi admin.',
            'deposit' => $deposit,
        ]);
    }

    /**
     * Cek apakah user sudah punya deposit awal/pending
     */
    public function checkPending()
    {
        $user = Auth::user();

        $pending = Investment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        $confirmed = Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->orWhere('status', 'success')
            ->exists();

        return response()->json([
            'hasPending' => $pending,
            'hasConfirmed' => $confirmed,
        ]);
    }
}
