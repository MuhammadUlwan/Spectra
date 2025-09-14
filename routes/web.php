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

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminInvestmentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AdminWithdrawController;
use App\Http\Controllers\Admin\AdminProfileController;

use App\Models\Announcement;

// ========================
// Default Route
// ========================
Route::get('/', function () {
    return redirect()->route('login');
});

// ========================
// Auth Routes
// ========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ========================
// Protected Routes
// ========================
Route::middleware('auth')->group(function () {

    // ========================
    // Dashboard umum (Investor / Admin)
    // ========================
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $walletBalance  = $user->wallet ?? 0;
        $depositBalance = method_exists($user, 'deposits') ? $user->deposits()->sum('amount') : 0;
        $totalProfit    = method_exists($user, 'profits') ? $user->profits()->sum('profit_amount') : 0;

        $announcements = \App\Models\Announcement::where('active', true)
            ->orderBy('order', 'asc')
            ->get()
            ->map(fn($a) => [
                'id'        => $a->id,
                'title'     => $a->title,
                'image_url' => $a->image_url,
            ]);

        // 🔥 Pisahkan admin vs investor
        if ($user->is_admin) {
            return Inertia::render('Admin/Dashboard', [
                'auth'          => ['user' => $user],
                'stats'         => [
                    'walletBalance'  => $walletBalance,
                    'depositBalance' => $depositBalance,
                    'totalProfit'    => $totalProfit,
                ],
                'announcements' => $announcements,
                'logoutUrl'     => route('logout'),
                'profileUrl'    => route('admin.profile'),
            ]);
        }

        return Inertia::render('Investor/DashboardInvestor', [
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
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');
    Route::post('/investments', [InvestmentController::class, 'store'])->name('investments.store');

    // ========================
    // Admin Routes
    // ========================
    Route::middleware(['isAdmin'])->prefix('admin')->group(function () {

        // ========================
        // Admin Profile
        // ========================
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
        Route::post('/profile/notifications', [AdminProfileController::class, 'updateNotifications'])->name('admin.profile.notifications');
        Route::post('/profile/preferences', [AdminProfileController::class, 'updatePreferences'])->name('admin.profile.preferences');

        // Admin Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // Admin Investments
        Route::get('/investments', [AdminInvestmentController::class, 'index'])->name('admin.investments.index');
        Route::get('/investments/{id}', [AdminInvestmentController::class, 'show'])->name('admin.investments.show');
        Route::post('/investments/{id}/update-status', [AdminInvestmentController::class, 'updateStatus'])->name('admin.investments.updateStatus');

        // Admin Users
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::post('/users/{id}/update-status', [AdminUserController::class, 'updateStatus'])->name('admin.users.updateStatus');
        Route::post('/users/{id}/update', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::post('/users/{id}/generate-wallet', [AdminUserController::class, 'generateWallet'])->name('admin.users.generateWallet');

        // Admin Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::post('/settings/update', [SettingController::class, 'update'])->name('admin.settings.update');

        // Admin Withdraws
        Route::get('/withdraws', [AdminWithdrawController::class, 'index'])->name('withdraws.index');
        Route::get('/withdraws/{id}', [AdminWithdrawController::class, 'show'])->name('withdraws.show');
        Route::post('/withdraws/{id}/process', [AdminWithdrawController::class, 'process'])->name('withdraws.process');

        // Admin Announcements
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
        Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
        Route::post('/announcements/{id}/toggle', [AnnouncementController::class, 'toggle'])->name('admin.announcements.toggle');
    });

    // ========================
    // Profile (Investor)
    // ========================
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications');
    Route::post('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');

    // ========================
    // Tarik Tunai
    // ========================
    Route::get('/tarik-tunai', [TarikTunaiController::class, 'index'])->name('tarik.index');
    Route::post('/tarik-tunai', [TarikTunaiController::class, 'store'])->name('tarik.store');

// ========================
// Deposit Routes (Investor)
// ========================
Route::middleware(['auth'])->group(function () {
    // Tampilkan halaman deposit
    Route::get('/deposit', [DepositController::class, 'index'])->name('deposit.index');

    // Submit deposit (store)
    Route::post('/deposit', [DepositController::class, 'store'])->name('deposit.store');
});

// Dompet (Wallet)
Route::get('/dompet', [WalletController::class, 'index'])->name('dompet');
});
