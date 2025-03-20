<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function notice() {
        return Inertia::render('Auth/VerifyEmail', [
            'status' => session('status'),
            'customer' => Auth::guard('customer')->user(),
        ]);
    }

    public function verify(EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('customer.register.process');
    }

    public function send(Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Verification link sent');
    }
}
