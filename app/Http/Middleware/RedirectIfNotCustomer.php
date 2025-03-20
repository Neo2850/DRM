<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotCustomer
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->guard('customer')->check()) {
            return redirect()->route('customer.login');
        }
        return $next($request);
    }
}
