<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Setting;

class DepositController extends Controller
{
    // Halaman daftar paket deposit/investasi
    public function index()
    {
        $settings = Setting::pluck('value','key_name')->toArray();
        $user = Auth::user();

        $packages = DB::table('investment_packages')
            ->select('id','name','amount','duration_months','profit_percent','currency')
            ->get()
            ->map(function($p) use ($settings){
                $p->profit_15days = round($p->amount * (floatval($settings['profit_percent_15days'] ?? 3.25) / 100), 2);
                $p->profit_monthly = round($p->amount * (floatval($settings['profit_percent'] ?? 7.5) / 100), 2);
                $p->total_profit = round($p->profit_monthly * $p->duration_months, 2);
                return $p;
            });

        return inertia('Investor/Deposit', [
            'user' => $user,
            'packages' => $packages,
            'settings' => $settings,
            'profileUrl' => route('profile'),
            'logoutUrl' => route('logout')
        ]);
    }

    // Simpan deposit ke database
    public function store(Request $request)
    {
        $request->validate([
            'package_ids' => 'required|json',
            'transfer_date' => 'required|date',
            'transfer_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $user = Auth::user();
        $packageIds = json_decode($request->package_ids, true);

        if (empty($packageIds)) {
            return back()->withErrors(['Paket tidak valid.']);
        }

        $file = $request->file('transfer_file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/deposits');
        if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);
        $file->move($destinationPath, $filename);
        $proofPath = 'deposits/' . $filename;

        foreach ($packageIds as $id) {
            $package = DB::table('investment_packages')->where('id', $id)->first();
            if (!$package) continue;

            DB::table('investments')->insert([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'amount' => $package->amount,
                'start_date' => null,
                'end_date' => null,
                'status' => 'pending',
                'proof_transfer' => $proofPath,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 🔹 Redirect Inertia ke dashboard langsung
        return redirect()->route('dashboard')->with('success', 'Deposit berhasil! Menunggu konfirmasi admin.');
    }
}
