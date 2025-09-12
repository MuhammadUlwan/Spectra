<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Show Login Page
     */
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle Login
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Solusi fix: pakai Inertia::location agar frontend langsung redirect
            return Inertia::location(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Show Register Page
     */
    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle Register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'username'         => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'wa'               => ['required', 'string', 'max:20'],
            'konsultan_kode'   => ['nullable', 'string', 'max:50'],
            'password'         => ['required', 'string', 'min:6'],
        ]);

        // Simpan user baru
        $user = User::create([
            'name'          => $validated['name'],
            'username'      => $validated['username'],
            'email'         => $validated['email'],
            'phone'         => $validated['wa'],
            'password'      => bcrypt($validated['password']),
            'role'          => 'investor',
            'is_consultant' => 0,
            'referral_code' => $validated['konsultan_kode'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location(route('login'));
    }
}
