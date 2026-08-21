<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $certificate->title }} - {{ $student->full_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Great+Vibes&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            padding: 20px 0;
        }
        .action-bar {
            max-width: 900px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 9999px;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #374151;
        }
        .btn-primary {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }
        .cert-container {
            width: 297mm;
            min-height: 210mm; /* A4 Landscape */
            margin: 0 auto;
            background: #ffffff;
            padding: 12mm;
            position: relative;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .cert-border-outer {
            border: 6px double #b45309;
            height: 100%;
            padding: 6mm;
            position: relative;
            background: #ffffff;
        }
        .cert-border-inner {
            border: 1.5px solid #d97706;
            height: 100%;
            padding: 8mm 12mm;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            width: 320px;
            pointer-events: none;
            z-index: 0;
        }
        .cert-header {
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .school-logo {
            max-height: 65px;
            margin-bottom: 6px;
        }
        .school-name {
            font-family: 'Cinzel', serif;
            font-size: 24pt;
            font-weight: 800;
            color: #78350f;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .school-info {
            font-size: 9.5pt;
            color: #4b5563;
        }
        .cert-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #d97706;
            font-size: 9pt;
            color: #6b7280;
            font-weight: 500;
        }
        .cert-title-wrap {
            text-align: center;
            margin: 12px 0 16px 0;
            position: relative;
            z-index: 1;
        }
        .cert-title {
            font-family: 'Cinzel', serif;
            font-size: 18pt;
            font-weight: 700;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: inline-block;
            padding: 4px 25px;
            border-bottom: 2px solid #b45309;
        }
        .cert-body {
            font-size: 11.5pt;
            line-height: 1.85;
            color: #1f2937;
            text-align: justify;
            position: relative;
            z-index: 1;
            margin: 10px 0;
        }
        .highlight {
            font-weight: 700;
            color: #111827;
            border-bottom: 1px dotted #9ca3af;
            padding: 0 4px;
        }
        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }
        .sig-block {
            text-align: center;
            width: 180px;
        }
        .sig-line {
            border-top: 1.5px solid #4b5563;
            margin-top: 40px;
            padding-top: 4px;
            font-weight: 600;
            font-size: 9.5pt;
            color: #374151;
        }
        .qr-section {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .qr-caption {
            font-size: 7.5pt;
            color: #6b7280;
            margin-top: 4px;
            font-weight: 500;
        }
        .revoked-banner {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 48pt;
            font-weight: 900;
            color: rgba(220, 38, 38, 0.35);
            border: 8px solid rgba(220, 38, 38, 0.35);
            padding: 10px 40px;
            text-transform: uppercase;
            letter-spacing: 5px;
            z-index: 10;
            pointer-events: none;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .cert-container {
                box-shadow: none !important;
                margin: 0;
                width: 100%;
                min-height: 100vh;
                padding: 0;
            }
            @page {
                size: A4 landscape;
                margin: 6mm;
            }
        }
    </style>
</head>
<body>

<div class="action-bar">
    <a href="{{ route('sms.certificates.index') }}" class="btn">
        &larr; Back to Certificates
    </a>
    <div style="display: flex; gap: 10px;">
        <button onclick="window.print()" class="btn btn-primary">
            &#128438; Print Certificate
        </button>
    </div>
</div>

<div class="cert-container">
    <div class="cert-border-outer">
        <div class="cert-border-inner">
            @if($setting && $setting->logo)
                <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" class="watermark" alt="Watermark">
            @endif

            @if($certificate->status === 'revoked')
                <div class="revoked-banner">REVOKED</div>
            @endif

            {{-- Certificate Header --}}
            <div class="cert-header">
                @if($setting && $setting->logo)
                    <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" class="school-logo" alt="Logo">
                @endif
                <div class="school-name">{{ $setting->title ?? 'BLESSED SACRAMENT HIGHER SECONDARY SCHOOL' }}</div>
                <div class="school-info">
                    {{ $setting->address ?? 'Lalitpur, Nepal' }} | Phone: {{ $setting->phone ?? '+977-1-5555555' }} | Email: {{ $setting->email ?? 'info@school.edu.np' }}
                </div>

                <div class="cert-meta">
                    <div><strong>Certificate No:</strong> <span style="font-family:monospace; font-size:10pt;">{{ $certificate->certificate_no }}</span></div>
                    <div><strong>Date of Issue:</strong> {{ $certificate->issue_date->format('F d, Y') }}</div>
                </div>
            </div>

            {{-- Title --}}
            <div class="cert-title-wrap">
                <div class="cert-title">{{ $certificate->title }}</div>
            </div>

            {{-- Body Content --}}
            <div class="cert-body">
                This is to certify that 
                <span class="highlight">{{ strtoupper($student->full_name) }}</span>, 
                @if($student->gender == 'Female') daughter @else son @endif of 
                <span class="highlight">{{ $student->guardian?->guardian_name ?? ($student->father_name ?? 'N/A') }}</span>,
                bearing Admission/Registration No. <span class="highlight">{{ $student->admission_no ?? '#' . $student->id }}</span>,
                was a bonafide student of this institution in 
                <span class="highlight">{{ $enrollment?->academicClass?->name ?? 'Class N/A' }} ({{ $enrollment?->section?->name ?? 'Section N/A' }})</span>
                during the academic session <span class="highlight">{{ $certificate->metadata['session'] ?? date('Y') }}</span>.
                <br><br>
                @if($certificate->type === 'character')
                    During @if($student->gender == 'Female') her @else his @endif stay at this institution, @if($student->gender == 'Female') her @else his @endif moral character, conduct, and general behavior have been found to be <span class="highlight">{{ $certificate->metadata['conduct'] ?? 'Good and Exemplary' }}</span>.
                @elseif($certificate->type === 'transfer')
                    All institutional dues up to date have been fully settled. @if(!empty($certificate->metadata['reason'])) The reason for leaving the institution is <span class="highlight">{{ $certificate->metadata['reason'] }}</span>. @endif We wish @if($student->gender == 'Female') her @else him @endif the very best in all future endeavors.
                @elseif($certificate->type === 'merit')
                    This certificate is awarded in recognition of outstanding academic performance and meritorious achievement during the academic session.
                @else
                    @if(!empty($certificate->metadata['remarks']))
                        {{ $certificate->metadata['remarks'] }}
                    @else
                        This certificate is issued upon formal request for reference and institutional verification.
                    @endif
                @endif
                <br>
                We wish @if($student->gender == 'Female') her @else him @endif success in future educational pursuits and career aspirations.
            </div>

            {{-- Footer --}}
            <div class="cert-footer">
                <div class="sig-block">
                    <div class="sig-line">Prepared By / Class Teacher</div>
                </div>

                <div class="qr-section">
                    {!! \App\Services\QrCodeService::svg($certificate->verification_url, 75) !!}
                    <div class="qr-caption">Scan to Verify Authenticity</div>
                    <div style="font-size: 6.5pt; font-family: monospace; color:#9ca3af;">{{ $certificate->qr_token }}</div>
                </div>

                <div class="sig-block">
                    <div class="sig-line">Principal / Head of School</div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
