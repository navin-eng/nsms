<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Verification - {{ $setting->title ?? 'School SMS' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #333; }
        .verification-card { max-width: 600px; margin: 40px auto; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .status-header { padding: 30px 20px; text-align: center; color: white; }
        .status-success { background: linear-gradient(135deg, #10b981, #059669); }
        .status-error { background: linear-gradient(135deg, #ef4444, #dc2626); }
        
        .icon-circle { 
            width: 80px; height: 80px; border-radius: 50%; 
            background: rgba(255,255,255,0.2); 
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 15px; font-size: 40px;
        }

        .data-list { list-style: none; padding: 0; margin: 0; }
        .data-list li { 
            display: flex; padding: 12px 20px; border-bottom: 1px solid #f1f5f9; 
            align-items: center;
        }
        .data-list li:last-child { border-bottom: none; }
        .data-label { color: #64748b; font-size: 14px; width: 40%; font-weight: 500; }
        .data-value { color: #0f172a; font-weight: 600; font-size: 15px; flex-grow: 1; }
        
        .school-footer { text-align: center; padding: 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 13px; color: #64748b; }
        .school-logo { height: 40px; margin-bottom: 10px; }
        
        /* Animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-up { animation: slideUp 0.5s ease forwards; }
    </style>
</head>
<body>

<div class="container px-3">
    <div class="verification-card bg-white animate-up">
        
        @if($status === 'success')
            <div class="status-header status-success">
                <div class="icon-circle">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3 class="fw-bold mb-1">Authentic Document</h3>
                <p class="mb-0 opacity-75">This document has been verified by the institution.</p>
            </div>

            <div class="card-body p-0">
                <div class="p-3 text-center bg-light border-bottom border-light" style="font-size: 13px;">
                    <span class="badge bg-secondary mb-1">Document Type</span>
                    <h5 class="mb-0 fw-bold text-dark mt-1">{{ $documentType }}</h5>
                </div>

                <ul class="data-list">
                    @foreach($data as $key => $value)
                        <li>
                            <div class="data-label">{{ $key }}</div>
                            <div class="data-value">{{ $value }}</div>
                        </li>
                    @endforeach
                    <li>
                        <div class="data-label">Verified At</div>
                        <div class="data-value text-success"><i class="bi bi-clock me-1"></i>{{ now()->format('d M Y, h:i A') }}</div>
                    </li>
                </ul>
            </div>
            
        @else
            <div class="status-header status-error">
                <div class="icon-circle">
                    <i class="bi bi-shield-x"></i>
                </div>
                <h3 class="fw-bold mb-1">Verification Failed</h3>
                <p class="mb-0 opacity-75">We could not authenticate this document.</p>
            </div>
            <div class="card-body p-5 text-center">
                <p class="text-muted mb-0">{{ $message ?? 'The QR code or link is invalid, expired, or the document has been revoked.' }}</p>
                <a href="{{ url('/') }}" class="btn btn-outline-danger mt-4">Return Home</a>
            </div>
        @endif
        
        <div class="school-footer">
            @if($setting && $setting->logo)
                <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" alt="Logo" class="school-logo">
            @endif
            <div class="fw-bold text-dark">{{ $setting->title ?? 'School SMS' }}</div>
            <div>{{ $setting->address ?? 'Nepal' }}</div>
            <div class="mt-2 text-muted" style="font-size: 11px;">Powered by bless. SMS</div>
        </div>

    </div>
</div>

</body>
</html>
