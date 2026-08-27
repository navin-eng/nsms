<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ProviderAuditLog;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FAQRCode\Google2FA as Google2FAQRCode;

class ProviderMfaController extends Controller
{
    /**
     * Show the MFA Challenge screen during login.
     */
    public function showChallenge(Request $request)
    {
        // If they aren't logged in at all, go to login.
        if (!Auth::guard('provider')->check()) {
            return redirect()->route('provider.login');
        }

        // If they are already verified, go to dashboard.
        if ($request->session()->get('provider_mfa_verified')) {
            return redirect()->route('provider.dashboard');
        }

        $user = Auth::guard('provider')->user();
        if (!$user) {
            Auth::guard('provider')->logout();
            $request->session()->invalidate();
            return redirect()->route('provider.login');
        }

        return view('provider.auth.2fa', compact('user'));
    }

    /**
     * Verify the inputted code (handles both Email OTP and TOTP).
     */
    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $user = Auth::guard('provider')->user();

        if ($user->mfa_method === 'totp') {
            // First check Google2FA
            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey($user->totp_secret, $request->code);
            
            // If Google2FA fails, check backup codes
            if (!$valid && !empty($user->two_factor_recovery_codes)) {
                $recoveryCodes = json_decode($user->two_factor_recovery_codes, true) ?? [];
                
                foreach ($recoveryCodes as $index => $hashedCode) {
                    if (Hash::check($request->code, $hashedCode)) {
                        $valid = true;
                        // Remove the used code
                        unset($recoveryCodes[$index]);
                        $user->two_factor_recovery_codes = json_encode(array_values($recoveryCodes));
                        $user->save();
                        ProviderAuditLog::log('provider.login_mfa_backup', null, "Provider user {$user->name} used a backup code.");
                        break;
                    }
                }
            }

            if (!$valid) {
                return back()->withErrors(['code' => 'Invalid Authenticator or Backup code.']);
            }
        } else {
            // Email OTP check
            if ($user->two_factor_code !== $request->code || now()->greaterThan($user->two_factor_expires_at)) {
                return back()->withErrors(['code' => 'Invalid or expired OTP code.']);
            }
            
            // Clear the used OTP
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();
        }

        // Mark session as verified
        $request->session()->put('provider_mfa_verified', true);
        ProviderAuditLog::log('provider.login_mfa_success', null, "Provider user {$user->name} successfully verified MFA.");

        return redirect()->intended(route('provider.dashboard'));
    }

    /**
     * Resend the Email OTP.
     */
    public function resend(Request $request)
    {
        $user = Auth::guard('provider')->user();
        
        if ($user->mfa_method !== 'email') {
            return back()->with('error', 'Your account is configured to use an Authenticator app, not email.');
        }

        $code = random_int(100000, 999999);
        $user->two_factor_code = $code;
        $user->two_factor_expires_at = now()->addMinutes(10);
        $user->save();

        try {
            Mail::to($user->email)->send(new ProviderTwoFactorMail($code, $user->name));
        } catch (\Exception $e) {
            \Log::error("Failed to send Provider 2FA email: " . $e->getMessage());
        }

        if (app()->environment('local')) {
            $request->session()->flash('local_otp', $code);
        }

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    /**
     * Show MFA Security Settings (Dashboard).
     */
    public function securitySettings()
    {
        $user = Auth::guard('provider')->user();
        $google2fa = new Google2FAQRCode();
        
        $qrCodeUrl = null;
        $newSecret = null;

        if ($user->mfa_method === 'email') {
            $newSecret = session('totp_setup_secret');
            if (!$newSecret) {
                $newSecret = $google2fa->generateSecretKey();
                session(['totp_setup_secret' => $newSecret]);
            }
            $qrCodeUrl = $google2fa->getQRCodeInline('NSMS God Mode', $user->email, $newSecret);
        }

        return view('provider.users.security', compact('user', 'qrCodeUrl', 'newSecret'));
    }

    /**
     * Verify and Enable TOTP.
     */
    public function enableTotp(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'secret' => 'nullable|string',
        ]);
        
        $secret = $request->input('secret') ?: session('totp_setup_secret');
        if (!$secret) {
            return back()->with('error', 'Setup session expired. Please refresh the page and try again.');
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            // Generate 20 backup codes
            $plainCodes = [];
            $hashedCodes = [];
            for ($i = 0; $i < 20; $i++) {
                $code = strtoupper(Str::random(8));
                $plainCodes[] = $code;
                $hashedCodes[] = Hash::make($code);
            }

            session([
                'totp_secret_verified' => $secret,
                'totp_recovery_codes_plain' => $plainCodes,
                'totp_recovery_codes_hashed' => $hashedCodes
            ]);
            
            session()->forget('totp_setup_secret');

            return redirect()->route('provider.security.totp.recovery');
        }

        return back()->withErrors(['code' => 'Invalid code. Setup failed.']);
    }

    /**
     * Show Recovery Codes Page.
     */
    public function showRecoveryCodes()
    {
        if (!session('totp_recovery_codes_plain')) {
            return redirect()->route('provider.security.settings');
        }

        return view('provider.users.recovery-codes');
    }

    /**
     * Finalize TOTP Setup.
     */
    public function confirmRecoveryCodes()
    {
        $secret = session('totp_secret_verified');
        $hashedCodes = session('totp_recovery_codes_hashed');

        if (!$secret || !$hashedCodes) {
            return redirect()->route('provider.security.settings')->with('error', 'Setup session expired.');
        }

        $user = Auth::guard('provider')->user();
        $user->totp_secret = $secret;
        $user->two_factor_recovery_codes = json_encode($hashedCodes);
        $user->mfa_method = 'totp';
        $user->save();
        
        session()->forget(['totp_secret_verified', 'totp_recovery_codes_plain', 'totp_recovery_codes_hashed']);
        
        ProviderAuditLog::log('provider.mfa_updated', null, "Provider user {$user->name} switched to TOTP MFA.");

        return redirect()->route('provider.security.settings')->with('success', 'Authenticator App and Recovery Codes successfully enabled!');
    }

    /**
     * Revert back to Email OTP.
     */
    public function revertToEmail()
    {
        $user = Auth::guard('provider')->user();
        $user->mfa_method = 'email';
        $user->totp_secret = null;
        $user->save();

        ProviderAuditLog::log('provider.mfa_updated', null, "Provider user {$user->name} reverted to Email OTP.");
        return back()->with('success', 'MFA reverted to Email OTP.');
    }
}
