<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTenantActive
{
    public function handle(Request $request, Closure $next)
    {
        $school = TenantContext::school();

        if (!$school) {
            $this->logoutTenantGuards();

            return redirect($request->is('accounting/*') ? route('accounting.login') : '/admin/dashboard/login')
                ->with('error', 'A valid school tenant is required to access this portal.');
        }

        if (!$school->isOperational()) {
            $this->logoutTenantGuards();

            return redirect($request->is('accounting/*') ? route('accounting.login') : '/admin/dashboard/login')
                ->with('error', "Access restricted: Institution status is '" . ucfirst($school->status) . "'.");
        }

        session(['tenant_school_id' => $school->id]);

        return $next($request);
    }

    private function logoutTenantGuards(): void
    {
        Auth::guard('web')->logout();
        Auth::guard('accounting')->logout();
    }
}
