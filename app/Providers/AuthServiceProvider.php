<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }  

    public function boot(): void
    {
        // 🔥 Login limit
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(50)->by($request->ip());
        });

        // 🔥 Appointment limit
        RateLimiter::for('appointments', function (Request $request) {
            return Limit::perMinute(20)
                ->by($request->user()?->id ?: $request->ip());
        });

        // 🔥 Doctor limit
        RateLimiter::for('doctors', function (Request $request) {
            return Limit::perMinute(100)->by($request->ip());
        });


        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        



    }
}