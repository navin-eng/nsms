<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: #333;
        }
        
        .invoice-container {
            position: relative;
            background: #fff;
            padding: 30px;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 8rem;
            color: rgba(0, 0, 0, 0.04);
            white-space: nowrap;
            font-weight: 900;
            z-index: 0;
            pointer-events: none;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }
        
        table th {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }

        .cut-line {
            display: flex;
            align-items: center;
            text-align: center;
            color: #999;
            margin: 20px 0;
            font-size: 11px;
            page-break-inside: avoid;
        }
        .cut-line::before, .cut-line::after {
            content: '';
            flex: 1;
            border-bottom: 1px dashed #999;
        }
        .cut-line span {
            padding: 0 10px;
        }

        /* Print Specifics */
        @media print {
            body { background: #fff; margin: 0; padding: 0; }
            .invoice-container { border: none; padding: 0; }
            .d-print-none { display: none !important; }
            
            @if($paperSize == 'a4')
                @page { size: A4 portrait; margin: 10mm; }
                .copy-wrapper { 
                    height: 48vh; /* Force half page */
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }
            @else
                @page { size: A5 landscape; margin: 10mm; }
                .cut-line { page-break-after: always; display: none; }
                .copy-wrapper {
                    height: 95vh;
                }
            @endif
        }
    </style>
</head>
<body>
    <div class="d-print-none text-center py-3 bg-light border-bottom mb-4">
        <button onclick="window.print()" class="btn btn-primary px-4"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer me-2" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/></svg> Print Invoice ({{ strtoupper($paperSize) }})</button>
        <button onclick="window.close()" class="btn btn-outline-secondary ms-2">Close</button>
    </div>

    @php
        $copies = ['ORIGINAL - STUDENT COPY', 'DUPLICATE - SCHOOL COPY'];
    @endphp

    <div class="container-fluid" style="max-width: 800px; margin: 0 auto;">
        @foreach($copies as $index => $copyText)
            <div class="copy-wrapper">
                <div class="invoice-container mb-3">
                    <div class="watermark">{{ explode(' - ', $copyText)[0] }}</div>
                    
                    <div class="content-wrapper">
                        <!-- Header -->
                        <div class="row align-items-center mb-3">
                            <div class="col-8">
                                <h4 class="fw-bold mb-1">BLESS ACADEMY</h4>
                                <p class="mb-0 text-muted small">Kathmandu, Nepal<br>Phone: +977-1-1234567 | Email: info@bless.edu.np</p>
                            </div>
                            <div class="col-4 text-end">
                                <h3 class="fw-bold text-uppercase mb-1" style="color: #444;">INVOICE</h3>
                                <p class="mb-0 fw-bold small text-primary">{{ $copyText }}</p>
                            </div>
                        </div>
                        
                        <hr class="mt-0 mb-3">

                        <!-- Details -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="fw-bold">Billed To:</div>
                                <div>{{ $invoice->student->first_name ?? '' }} {{ $invoice->student->last_name ?? '' }}</div>
                                <div>Reg No: {{ $invoice->student->registration_number ?? 'N/A' }}</div>
                                <div>Class: {{ $invoice->student->currentEnrollment->academicClass->name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6 text-end">
                                <div><span class="fw-bold">Invoice No:</span> #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</div>
                                <div><span class="fw-bold">Date:</span> {{ $invoice->created_at->format('M d, Y') }}</div>
                                <div><span class="fw-bold">Due Date:</span> {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}</div>
                                <div><span class="fw-bold">Academic Year:</span> {{ $invoice->academicYear->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        
                        <div class="mb-2 fw-bold bg-light p-2 text-center rounded">
                            {{ $invoice->nepali_month ? $invoice->nepali_month . ' - ' : '' }}{{ $invoice->title }}
                        </div>

                        <!-- Table -->
                        <table class="table table-bordered table-sm mb-2">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">S.N.</th>
                                    <th>Fee Description</th>
                                    <th class="text-end" style="width: 150px;">Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $i => $item)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>{{ $item->feeType->name ?? 'Fee' }}</td>
                                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <!-- Totals -->
                        <div class="row">
                            <div class="col-7">
                                @if($invoice->remarks)
                                    <div class="small mt-2 p-2 bg-light rounded text-muted">
                                        <strong>Remarks:</strong> {{ $invoice->remarks }}
                                    </div>
                                @endif
                                
                                <div class="mt-4 pt-3 border-top d-inline-block" style="width: 150px; text-align: center;">
                                    <small class="text-muted">Authorized Signature</small>
                                </div>
                            </div>
                            <div class="col-5">
                                <table class="table table-sm table-borderless mb-0 text-end">
                                    <tr>
                                        <td>Subtotal:</td>
                                        <td>{{ number_format($invoice->subtotal, 2) }}</td>
                                    </tr>
                                    @if($invoice->discount_amount > 0)
                                    <tr class="text-success">
                                        <td>Discount:</td>
                                        <td>- {{ number_format($invoice->discount_amount, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($invoice->previous_due > 0)
                                    <tr class="text-danger">
                                        <td>Previous Due:</td>
                                        <td>+ {{ number_format($invoice->previous_due, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="border-top fw-bold fs-6">
                                        <td>Total Due:</td>
                                        <td>Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($index == 0)
                <div class="cut-line">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-scissors" viewBox="0 0 16 16">
                      <path d="M3.5 3.5c-.614-.884-.074-1.962.858-2.5L8 7.226 11.642 1c.932.538 1.472 1.616.858 2.5L8.81 8.61l1.556 2.661a2.5 2.5 0 1 1-.794.637L8 9.73l-1.572 2.177a2.5 2.5 0 1 1-.794-.637L7.19 8.61 3.5 3.5zm2.5 10a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0zm7 0a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0z"/>
                    </svg>
                </div>
            @endif
        @endforeach
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
