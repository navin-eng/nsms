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
            'school_code' => 'required|string',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $schoolCode = strtoupper(trim($request->school_code));
        $school = \App\Models\School::where('school_code', $schoolCode)->first();

        if (!$school) {
            return back()->with('error', "Invalid School Code '{$schoolCode}'. Please check your institution code.")->withInput();
        }

        if (!$school->isOperational()) {
            return back()->with('error', "Your school account status is currently '" . ucfirst($school->status) . "'. Please contact the SaaS provider.")->withInput();
        }

        $credentials['school_id'] = $school->id;

        if (Auth::guard('accounting')->attempt($credentials)) {
            $request->session()->regenerate();
            session(['tenant_school_id' => $school->id]);
            
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
