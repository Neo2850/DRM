<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

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
// Redirect if authenticated
        RedirectIfAuthenticated::redirectUsing(function ($guard = null) {
    // Check the guard and redirect based on role
        $user = Auth::guard($guard)->user(); // Get the authenticated user
        if ($user && $user->role === 'admin') {
        return route('dashboard'); // Redirect admins to the dashboard
    }
    return route('customer.profile'); // Redirect customers to their profile
        });

        // Redirect unauthenticated users
        Authenticate::redirectUsing(function () {
            // Flash an error message
        Session::flash('error', 'You must be logged in to access this page.');

        // Redirect unauthenticated users to the appropriate login
        if (request()->is('admin/*')) {
            return route('admin.login'); // Admin routes redirect to admin login
        }
            return route('customer.login'); // Customer routes redirect to customer login
        });

    }
}
