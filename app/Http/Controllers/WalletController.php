<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil withdrawals user
        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($w) {
                $w->type = $w->type ?? 'withdraw';
                return $w;
            });

        // Ambil settings
        $settings = DB::table('settings')->get();

        // Buat filter transactionTypes
        $transactionTypes = ['withdraw']; // hardcode withdraw
        foreach ($settings as $s) {
            $key = $s->key_name;
            if (str_contains($key, 'profit_percent')) $transactionTypes[] = 'profit';
            if (str_contains($key, 'profit_sharing_level')) $transactionTypes[] = 'profit sharing';
            if (str_contains($key, 'bonus_profit')) $transactionTypes[] = 'bonus profit';
            if (str_contains($key, 'sponsor_fee')) $transactionTypes[] = 'referal qr';
        }
        $transactionTypes = array_unique($transactionTypes);

        return Inertia::render('Investor/Dompet', [
            'auth' => ['user' => $user],
            'withdrawals' => $withdrawals,
            'transactionTypes' => $transactionTypes,
            'settings' => $settings, // penting untuk filter di frontend
        ]);
    }
}
