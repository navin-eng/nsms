<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProviderAuditLog;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProviderTwoFactorMail;
use Illuminate\Support\Str;

class ProviderAuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if (Auth::guard('provider')->check()) {
            if (!$request->session()->get('provider_mfa_verified', false)) {
                return redirect()->route('provider.2fa.challenge');
            }
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

            // Generate and send Email OTP if they use email MFA
            if ($user->mfa_method === 'email') {
                $code = random_int(100000, 999999);
                $user->two_factor_code = $code;
                $user->two_factor_expires_at = now()->addMinutes(10);
                $user->save();

                try {
                    Mail::to($user->email)->send(new ProviderTwoFactorMail($code, $user->name));
                } catch (\Exception $e) {
                    // Ignore mail error locally, but log it
                    \Log::error("Failed to send Provider 2FA email: " . $e->getMessage());
                }
            }

            // Flag session as NOT verified yet
            $request->session()->put('provider_mfa_verified', false);

            if (app()->environment('local') && isset($code)) {
                $request->session()->flash('local_otp', $code);
            }

            ProviderAuditLog::log('provider.login_initiated', null, "Provider user {$user->name} initiated login. Pending MFA.");

            return redirect()->route('provider.2fa.challenge');
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
