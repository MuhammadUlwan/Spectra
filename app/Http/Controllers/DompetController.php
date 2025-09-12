<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DompetController extends Controller
{
    /**
     * Tampilkan halaman Dompet
     */
    public function index()
    {
        $user = auth()->user();

        // Contoh: transaksi bisa dikosongkan dulu
        $transactions = [
            // ['id' => 1, 'created_at' => now(), 'type' => 'deposit', 'amount' => 100000, 'status' => 'success'],
        ];

        return Inertia::render('Dompet', [
            'auth' => ['user' => $user],
            'transactions' => $transactions,
        ]);
    }
}
