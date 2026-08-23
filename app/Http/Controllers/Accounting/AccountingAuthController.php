<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiteSetting;

class AccountingAuthController extends Controller
{
    public function showLoginForm()
    {
        $siteSettings = SiteSetting::first();
        return view('accounting.auth.login', compact('siteSettings'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('accounting')->attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('accounting.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('accounting')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('accounting.login');
    }
}
