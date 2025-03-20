<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function create()
    {
        return Inertia::render("Auth/AdminLogin");
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            User::where('id', Auth::id())->update(['status' => 'Active']);

            return redirect()->intended('admin/dashboard');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function update(Request $request) {

    }
    public function destroy(Request $request) {

        User::where('id', Auth::id())->update(['status' => 'inactive']);
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
