<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Withdrawal;

class TarikTunaiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil metode tarik tunai user
        $paymentMethods = [];
        if ($user->bank_name && $user->bank_number) {
            $paymentMethods[] = (object)[
                'id' => 'bank',
                'name' => $user->bank_name . ' - ' . $user->bank_number
            ];
        }
        // Wallet selalu tersedia
        $paymentMethods[] = (object)[
            'id' => 'wallet',
            'name' => 'Wallet'
        ];

        return Inertia::render('Investor/TarikTunai', [
            'auth' => ['user' => $user],
            'paymentMethods' => $paymentMethods,
            'profileUrl' => route('profile'),
            'logoutUrl' => route('logout'),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $amount = (int) $request->input('amount', 0);
        $method = $request->input('method_id');

        if ($amount <= 0 || $amount > $user->wallet) {
            return redirect()->back()->with('error', 'Jumlah tarik tunai tidak valid.');
        }

        // Kurangi saldo
        $user->wallet -= $amount;
        $user->save(); // aman, walau IDE kadang underline

        // Simpan request withdrawal
        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'capital', // bisa disesuaikan
            'status' => 'pending',
            'withdraw_method' => $method,
        ]);

        return redirect()->back()->with('success', 'Permintaan tarik tunai berhasil, menunggu konfirmasi admin.');
    }
}
