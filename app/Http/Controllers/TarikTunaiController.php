<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TarikTunaiController extends Controller
{
    /**
     * Tampilkan halaman Tarik Tunai
     */
    public function index()
    {
        $user = Auth::user();

        $paymentMethods = []; // nanti diisi dari tabel payment_methods

        return Inertia::render('TarikTunai', [
            'auth' => [
                'user' => $user,
            ],
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Proses permintaan tarik tunai
     */
    public function store(Request $request)
    {
        // Agar frontend tidak error walau backend belum siap
        return redirect()->back()->with('success', 'Frontend test: request tarik tunai diterima (dummy).');
    }
}
