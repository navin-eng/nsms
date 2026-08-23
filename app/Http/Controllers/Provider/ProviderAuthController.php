<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProviderAuditLog;

class ProviderAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('provider')->check()) {
            return redirect()->route('provider.dashboard');
        }

        return view('provider.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('provider')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::guard('provider')->user();

            if (!$user->is_active) {
                Auth::guard('provider')->logout();
                return back()->withErrors(['email' => 'Your SaaS Provider administrator account has been deactivated.']);
            }

            ProviderAuditLog::log('provider.login', null, "Provider user {$user->name} logged into God Mode.");

            return redirect()->intended(route('provider.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid Provider credentials provided.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('provider')->user();
        if ($user) {
            ProviderAuditLog::log('provider.logout', null, "Provider user {$user->name} logged out.");
        }

        Auth::guard('provider')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('provider.login')->with('success', 'Logged out of SaaS Provider portal.');
    }
}
