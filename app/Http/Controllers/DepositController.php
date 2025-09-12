<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Deposit', [
            'auth' => [
                'user' => $user,
            ],
            'wallet' => $user->wallet ?? 0,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        $user = Auth::user();
        $user->wallet = ($user->wallet ?? 0) + $request->amount;
        $user->save();

        return redirect()->route('deposit.index')->with('success', 'Deposit berhasil ditambahkan!');
    }
}
