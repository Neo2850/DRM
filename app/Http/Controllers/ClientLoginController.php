<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;

class ClientLoginController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function create() {
        return Inertia::render("Auth/Login");
    }

    public function store(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Migrate guest cart to user cart
            $this->cartService->migrateGuestCartToUser(Auth::guard('customer')->id());

            if (!Auth::guard('customer')->user()->email_verified_at) {
                return redirect()->route('verification.notice');
            }

            return redirect()->intended(route('home'));
        }

        // Check if email exists first
        $customer = \App\Models\Customer::where('email', $request->email)->first();

        if ($customer) {
            return back()->withErrors([
                'password' => 'The password is incorrect.'
            ])->onlyInput('password');
        }

        return back()->withErrors([
            'email' => 'The email address does not match our records.'
        ])->onlyInput('email');
    }

    public function destroy(Request $request) {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
