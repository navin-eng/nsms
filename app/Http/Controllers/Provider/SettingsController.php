<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        abort_unless(auth('provider')->user()->can('provider_technical_tools'), 403, 'Unauthorized access.');

        $settings = \App\Models\ProviderSetting::pluck('value', 'key')->toArray();
        return view('provider.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        abort_unless(auth('provider')->user()->can('provider_technical_tools'), 403, 'Unauthorized access.');

        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'company_pan_vat' => 'required|string|max:255',
            'tax_type' => 'required|in:exclusive,inclusive,none',
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        \App\Models\ProviderSetting::set('company_name', $request->company_name);
        \App\Models\ProviderSetting::set('company_address', $request->company_address);
        \App\Models\ProviderSetting::set('company_pan_vat', $request->company_pan_vat);
        \App\Models\ProviderSetting::set('tax_type', $request->tax_type);
        \App\Models\ProviderSetting::set('tax_rate', $request->tax_rate);

        return back()->with('success', 'Billing settings updated successfully.');
    }
}
