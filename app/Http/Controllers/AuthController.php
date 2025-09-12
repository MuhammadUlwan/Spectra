<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }

        return Inertia::render('Auth/Login');
    }

    // Proses login - FIXED
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Return Inertia response untuk redirect
            return Inertia::location(route($this->getDashboardRoute(Auth::user())));
        }

        // Kembalikan ke halaman login dengan errors
        return Inertia::render('Auth/Login', [
            'errors' => ['email' => 'Email atau password salah']
        ]);
    }

    // Tampilkan halaman register
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }

        return Inertia::render('Auth/Register');
    }

    // Proses register - FIXED
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:admin,investor,konsultan']
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Auth::login($user);

        // Return Inertia response untuk redirect
        return Inertia::location(route($this->getDashboardRoute($user)));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location(route('login'));
    }

    /**
     * Get dashboard route based on user role
     */
    private function getDashboardRoute(User $user): string
    {
        return match($user->role) {
            'admin' => 'admin.dashboard',
            default => 'dashboard'
        };
    }

    /**
     * Redirect user ke dashboard sesuai role (for non-Inertia requests)
     */
    private function redirectToDashboard(User $user)
    {
        return redirect()->route($this->getDashboardRoute($user));
    }
}