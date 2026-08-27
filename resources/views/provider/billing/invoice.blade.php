<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tax Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; margin: 0; padding: 20px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0; font-size: 14px; }
        .details-table { width: 100%; margin-bottom: 30px; }
        .details-table td { vertical-align: top; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th, .items-table td { padding: 10px; border: 1px solid #ddd; }
        .items-table th { background: #f5f5f5; text-align: left; }
        .items-table td.money { text-align: right; }
        .totals-table { width: 50%; float: right; border-collapse: collapse; }
        .totals-table td { padding: 8px; border: 1px solid #ddd; }
        .totals-table td.money { text-align: right; }
        .totals-table td.label { font-weight: bold; background: #f5f5f5; }
        .clearfix::after { content: ""; clear: both; display: table; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
        .signature { margin-top: 80px; width: 250px; float: right; text-align: center; border-top: 1px solid #333; padding-top: 5px; }
        .status-badge { display: inline-block; padding: 5px 10px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 12px; }
        .status-paid { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        
        @media print {
            .no-print { display: none; }
            .invoice-box { box-shadow: none; border: 0; margin: 0; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #0d6efd; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Print Invoice</button>
        <a href="{{ route('provider.billing.download', $invoice->id) }}" style="padding: 10px 20px; background: #198754; color: #fff; text-decoration: none; border-radius: 5px; margin-left: 10px;">Download PDF</a>
    </div>

    <div class="invoice-box">
        <div class="header">
            <h1>TAX INVOICE</h1>
            <h2 style="margin: 10px 0;">{{ $settings['company_name'] ?? 'Nepal School Management System (NSMS)' }}</h2>
            <p>{{ $settings['company_address'] ?? 'Kathmandu, Nepal' }}</p>
            <p class="bold">PAN/VAT No: {{ $settings['company_pan_vat'] ?? 'N/A' }}</p>
        </div>

        <table class="details-table">
            <tr>
                <td>
                    <p class="bold" style="text-decoration: underline; margin-bottom: 5px;">Bill To:</p>
                    <p class="bold" style="font-size: 16px;">{{ $invoice->school->name }}</p>
                    <p>{{ $invoice->school->address }}</p>
                    <p>Phone: {{ $invoice->school->phone }}</p>
                    <p>Code: {{ $invoice->school->school_code }}</p>
                </td>
                <td class="text-right">
                    <p><span class="bold">Invoice No:</span> {{ $invoice->invoice_number }}</p>
                    <p><span class="bold">Date:</span> {{ $invoice->created_at->format('Y-m-d') }}</p>
                    <p><span class="bold">Billing Cycle:</span> {{ ucfirst($invoice->billing_cycle) }}</p>
                    <p><span class="bold">Subscription:</span> {{ \Carbon\Carbon::parse($invoice->subscription_start)->format('Y-m-d') }} to {{ \Carbon\Carbon::parse($invoice->subscription_end)->format('Y-m-d') }}</p>
                    <p style="margin-top: 10px;">
                        @if($invoice->status === 'paid')
                            <span class="status-badge status-paid">PAID</span>
                        @else
                            <span class="status-badge status-pending">PENDING</span>
                        @endif
                    </p>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Description</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Rate (NPR)</th>
                    <th class="text-right">Amount (NPR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Software Subscription - {{ $invoice->package_name }} Package</td>
                    <td class="text-center">1</td>
                    <td class="money">{{ number_format($invoice->subtotal, 2) }}</td>
                    <td class="money">{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="clearfix">
            <table class="totals-table">
                <tr>
                    <td class="label">Gross Amount</td>
                    <td class="money">{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Discount</td>
                    <td class="money">{{ number_format($invoice->discount, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Taxable Amount</td>
                    <td class="money">{{ number_format($invoice->subtotal - $invoice->discount, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">VAT (13%)</td>
                    <td class="money">{{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="label" style="font-size: 16px;">Grand Total</td>
                    <td class="money bold" style="font-size: 16px;">{{ number_format($invoice->amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="clearfix">
            <div class="signature">
                Authorized Signature
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This is a computer generated invoice and does not require a physical signature.</p>
        </div>
    </div>
</body>
</html>
