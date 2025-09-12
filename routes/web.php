<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TarikTunaiController;
use App\Models\Announcement;

// Default Route
Route::get('/', fn() => redirect()->route('login'));

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {

    // ========================
    // Dashboard Investor/Konsultan
    // ========================
    Route::get('/dashboard', function () {
        $user = Auth::user();

        $walletBalance  = $user->wallet ?? 0;
        $depositBalance = $user->deposits()->sum('amount');
        $totalProfit    = $user->profits()->sum('profit_amount');

        $announcements = Announcement::where('active', true)
            ->orderBy('order', 'asc')
            ->get()
            ->map(fn($a) => [
                'id'        => $a->id,
                'title'     => $a->title,
                'image_url' => $a->image_url,
            ]);

        return Inertia::render('Dashboard', [
            'auth'           => ['user' => $user],
            'walletBalance'  => $walletBalance,
            'depositBalance' => $depositBalance,
            'totalProfit'    => $totalProfit,
            'announcements'  => $announcements,
            'logoutUrl'      => route('logout'),
            'profileUrl'     => route('profile'),
        ]);
    })->name('dashboard');

    // ========================
    // Investor Routes
    // ========================
    Route::get('/investments', [InvestmentController::class, 'index'])
        ->name('investments.index');
    Route::post('/investments', [InvestmentController::class, 'store'])
        ->name('investments.store');

    // ========================
    // Admin Routes
    // ========================
    Route::middleware('isAdmin')->group(function () {
        // Admin Dashboard
        Route::get('/admin/dashboard', function () {
            $user = Auth::user();

            return Inertia::render('Admin/Dashboard', [
                'auth'       => ['user' => $user],
                'logoutUrl'  => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.dashboard');

            // Manajemen Pengguna
        Route::get('/admin/manajemen_users', function () {
            $user = Auth::user();
            return Inertia::render('Admin/AdminUsers', [
                'auth'       => ['user' => $user],
                'logoutUrl'  => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.users.index');

            // Manajemen Deposit
        Route::get('/admin/deposits', function () {
            $user = Auth::user();
            return Inertia::render('Admin/AdminDeposits', [
                'auth'       => ['user' => $user],
                'logoutUrl'  => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.deposits.index');

            // Manajemen Withdraw
        Route::get('/admin/withdraws', function () {
            $user = Auth::user();
            return Inertia::render('Admin/AdminWithdraws', [
                'auth'       => ['user' => $user],
                'logoutUrl'  => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.withdraws.index');

            // Manajemen kontrak
        Route::get('/admin/contracts', function () {
            $user = Auth::user();
            return Inertia::render('Admin/AdminContracts', [
                'auth'       => ['user' => $user],
                'logoutUrl'  => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.contracts.index');

            // Manajemen profit
        Route::get('/admin/profit', function () {
            $user = Auth::user();
            return Inertia::render('Admin/AdminProfit', [
                'auth'       => ['user' => $user],
                'logoutUrl'  => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.profit.index');

            // Manajemen komisi & bonus
        Route::get('/admin/commissions', function () {
            $user = Auth::user();
            return Inertia::render('Admin/AdminCommissions', [
                'auth'       => ['user' => $user],
                'logoutUrl'  => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.commissions.index');

            // Manajemen laporan
        Route::get('/admin/reports', function () {
            $user = Auth::user();
            return Inertia::render('Admin/AdminReports', [
                'auth' => ['user' => $user],
                'logoutUrl' => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.reports.index');

            // Manajemen settings
        Route::get('/admin/settings', function () {
            $user = Auth::user();
            return Inertia::render('Admin/AdminSettings', [
                'auth' => ['user' => $user],
                'logoutUrl' => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.settings');

            // Manajemen notifikasi 
        Route::get('/admin/notifications', function () {
            $user = Auth::user();
            return Inertia::render('Admin/AdminNotifications', [
                'auth' => ['user' => $user],
                'logoutUrl' => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.notifications');

            // Manajemen audit
        Route::get('/admin/audit', function () {
            $user = Auth::user();
            return Inertia::render('Admin/AdminAudit', [
                'auth' => ['user' => $user],
                'logoutUrl' => route('logout'),
                'profileUrl' => route('profile'),
            ]);
        })->name('admin.audit');

        // Admin Investments
        Route::get('/admin/investments', [InvestmentController::class, 'adminIndex'])
            ->name('admin.investments.index');
        Route::post('/admin/investments/{id}/validate', [InvestmentController::class, 'validateInvestment'])
            ->name('admin.investments.validate');

        // Admin Announcements
        Route::get('/admin/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])
            ->name('admin.announcements.index');
        Route::post('/admin/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])
            ->name('admin.announcements.store');
        Route::delete('/admin/announcements/{id}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])
            ->name('admin.announcements.destroy');
        Route::post('/admin/announcements/{id}/toggle', [\App\Http\Controllers\Admin\AnnouncementController::class, 'toggle'])
            ->name('admin.announcements.toggle');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications');
    Route::post('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');

    // Tarik Tunai
    Route::get('/tarik-tunai', [TarikTunaiController::class, 'index'])->name('tarik.index');
    Route::post('/tarik-tunai', [TarikTunaiController::class, 'store'])->name('tarik.store');

    // Deposit & Wallet
    Route::get('/deposit', [DepositController::class, 'index'])->name('deposit');
    Route::post('/deposit', [DepositController::class, 'store'])->name('deposit.store');
    Route::get('/dompet', [WalletController::class, 'index'])->name('dompet');
});
