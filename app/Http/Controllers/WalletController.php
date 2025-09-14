<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WalletController extends Controller
{
    public function index()
    {
        return Inertia::render('Dompet', [
            'auth'          => ['user' => Auth::user()],
            // Dummy data dulu, nanti bisa tarik dari tabel transactions/withdrawals
            'transactions'  => [
                [
                    'id'         => 1,
                    'type'       => 'deposit',
                    'amount'     => 1000000,
                    'status'     => 'success',
                    'created_at' => now(),
                ],
                [
                    'id'         => 2,
                    'type'       => 'withdrawal',
                    'amount'     => 500000,
                    'status'     => 'pending',
                    'created_at' => now(),
                ],
            ],
            'walletAddress' => 'SAMPLE-WALLET-ADDRESS-123456',
            'qrCodeUrl'     => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=SAMPLE-WALLET-ADDRESS-123456',
        ]);
    }
}
