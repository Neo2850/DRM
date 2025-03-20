<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerIsAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.register')->with('error', 'You must be logged in as a customer.');
        }

        return $next($request);
    }
}
