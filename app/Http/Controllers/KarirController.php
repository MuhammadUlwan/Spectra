<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class KarirController extends Controller
{
    /**
     * Display the career page for investors.
     */
    public function index()
    {
        return Inertia::render('Investor/Karir', [
            'auth' => ['user' => auth()->user()],
        ]);
    }
}