<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan URL API global ke semua view / Inertia
        $apiBaseUrl = URL::to('/api');

        // Ambil referral code dari query string, misal: /register?ref=KODE123
        $referralCode = Request::query('ref', null);

        // Default role user (softcode)
        $defaultUserRole = 'investor';

        // Share ke semua view biasa
        View::share([
            'apiBaseUrl'      => $apiBaseUrl,
            'referralCode'    => $referralCode,
            'defaultUserRole' => $defaultUserRole,
        ]);

        // Share juga ke Inertia props
        Inertia::share([
            'apiBaseUrl'      => $apiBaseUrl,
            'referralCode'    => $referralCode,
            'defaultUserRole' => $defaultUserRole,
        ]);
    }
}
