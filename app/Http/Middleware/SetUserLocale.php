<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetUserLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->preference) {
            $locale = Auth::user()->preference->language ?? 'id';
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
