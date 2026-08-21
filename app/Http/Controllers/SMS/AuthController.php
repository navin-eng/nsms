<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiteSetting;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->a_type === 'S') {
            return redirect()->route('sms.dashboard');
        }
        if (Auth::check() && in_array(Auth::user()->a_type, ['A', 'E'])) {
            return redirect()->route('admin.portal');
        }

        $siteSettings = SiteSetting::current();
        return view('backend.auth.sms-login', compact('siteSettings'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, true)) {
            $user = Auth::user();

            // Only allow SMS staff users through this login
            if ($user->a_type !== 'S') {
                Auth::logout();
                return back()->with('error', 'This login is for staff only. Please use the admin login.');
            }

            return redirect()->route('sms.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->with('error', 'Invalid email or password. Please try again.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('sms.login')->with('success', 'You have been logged out successfully.');
    }
}
