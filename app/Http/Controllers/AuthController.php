<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        return inertia('Auth/Login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return inertia()->location('/admin/dashboard');
            } elseif ($user->role === 'investor') {
                return inertia()->location('/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Role tidak dikenali.']);
            }
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    /**
     * Tampilkan halaman register
     */
    public function showRegister()
    {
        return inertia('Auth/Register');
    }

    /**
     * Proses register
     */
// Handle register
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'wa' => ['required', 'string', 'max:20'], // ini akan disimpan di 'phone'
            'konsultan_kode' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        // Cek kode konsultan jika diisi
        $consultantId = null;
        if (!empty($validated['konsultan_kode'])) {
            $consultant = User::where('referral_code', $validated['konsultan_kode'])->first();
            if (!$consultant) {
                return response()->json([
                    'errors' => ['konsultan_kode' => 'Kode konsultan tidak valid']
                ], 422);
            }
            $consultantId = $consultant->id;
        }

        // Generate referral code unik
        do {
            $referralCode = strtoupper(Str::random(6));
        } while (User::where('referral_code', $referralCode)->exists());

        try {
            // Simpan user baru
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['wa'], // disimpan di kolom phone
                'password' => bcrypt($validated['password']),
                'role' => 'investor',
                'is_consultant' => 0,
                'referral_code' => $referralCode,
                'konsultan_id' => $consultantId,
                'is_active' => 1,
            ]);

            // Login otomatis
            Auth::login($user);

            return response()->json([
                'success' => true,
                'referral_code' => $user->referral_code,
                'dashboard' => route('dashboard'),
            ]);
        } catch (\Exception $e) {
            // Tangani error server, kirim ke frontend
            return response()->json([
                'errors' => ['server' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return inertia()->location(route('login'));
    }
}
