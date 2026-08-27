<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireProviderMFA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only run if user is logged into the provider guard
        if (Auth::guard('provider')->check()) {
            $user = Auth::guard('provider')->user();
            if (!$user) {
                Auth::guard('provider')->logout();
                $request->session()->invalidate();
                return redirect()->route('provider.login');
            }
            
            // If the user hasn't verified MFA yet, block access
            if (!$request->session()->get('provider_mfa_verified', false)) {
                // Allow them to visit the 2FA challenge routes, logout, and assets
                if (!$request->is('provider/2fa*') && !$request->is('provider/logout') && !$request->is('provider/login')) {
                    return redirect()->route('provider.2fa.challenge');
                }
            }
        }

        return $next($request);
    }
}
