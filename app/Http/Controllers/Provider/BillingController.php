<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProviderInvoice;
use App\Models\ProviderSetting;
use PDF; // Barryvdh\DomPDF\Facade\Pdf

class BillingController extends Controller
{
    public function printInvoice($id)
    {
        abort_unless(auth('provider')->user()->can('provider_manage_billing'), 403, 'Unauthorized access.');

        $invoice = ProviderInvoice::with('school')->findOrFail($id);
        $settings = ProviderSetting::pluck('value', 'key')->toArray();

        return view('provider.billing.invoice', compact('invoice', 'settings'));
    }

    public function downloadPdf($id)
    {
        abort_unless(auth('provider')->user()->can('provider_manage_billing'), 403, 'Unauthorized access.');

        $invoice = ProviderInvoice::with('school')->findOrFail($id);
        $settings = ProviderSetting::pluck('value', 'key')->toArray();

        $pdf = PDF::loadView('provider.billing.invoice', compact('invoice', 'settings'));
        
        return $pdf->download($invoice->invoice_number . '.pdf');
    }
}
