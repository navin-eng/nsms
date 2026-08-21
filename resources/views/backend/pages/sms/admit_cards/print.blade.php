<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Admit Cards - {{ $exam->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f3f4f6; color: #111827; }
        .action-bar { max-width: 900px; margin: 20px auto; display: flex; justify-content: space-between; align-items: center; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; font-size: 14px; font-weight: 600; border-radius: 9999px; cursor: pointer; text-decoration: none; border: 1px solid #d1d5db; background: #ffffff; color: #374151; }
        .btn-primary { background: #2563eb; color: #ffffff; border-color: #2563eb; }
        
        .page-container {
            width: 210mm; /* A4 Width */
            margin: 0 auto;
            background: #ffffff;
            padding: 10mm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 20mm;
            display: grid;
            gap: 10mm;
        }

        @if($layout === 'a4_multiple')
            .page-container { grid-template-columns: repeat(2, 1fr); align-content: start; }
            .admit-card { height: 130mm; } /* Roughly 2x2 grid fits on A4 */
        @else
            .page-container { grid-template-columns: 1fr; align-content: start; }
            .admit-card { height: 135mm; } /* 2 per page, more width */
        @endif

        .admit-card {
            border: 2px solid #2563eb;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .card-header {
            text-align: center;
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            background: #f8fafc;
        }
        .school-name { font-weight: 700; font-size: 14pt; color: #1e40af; text-transform: uppercase; margin-bottom: 2px; }
        .school-address { font-size: 8pt; color: #6b7280; margin-bottom: 6px; }
        .badge { display: inline-block; background: #2563eb; color: white; padding: 4px 12px; border-radius: 999px; font-weight: bold; font-size: 9pt; letter-spacing: 1px; }
        .exam-name { font-weight: 700; font-size: 11pt; margin-top: 6px; color: #111827; }

        .card-body {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .student-section { display: flex; gap: 15px; margin-bottom: 15px; }
        .photo-box { width: 75px; height: 95px; border: 1px solid #d1d5db; padding: 2px; flex-shrink: 0; background: #fff; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; }
        .photo-placeholder { width: 100%; height: 100%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 24px; }
        
        .info-table { width: 100%; border-collapse: collapse; font-size: 9pt; line-height: 1.5; }
        .info-table td { padding: 3px 0; }
        .label { color: #6b7280; width: 80px; font-weight: 500; }
        .value { color: #111827; font-weight: 700; border-bottom: 1px dotted #d1d5db; }
        
        .instructions { font-size: 7.5pt; color: #4b5563; margin-top: auto; padding-top: 10px; border-top: 1px dashed #e5e7eb; line-height: 1.4; }
        .instructions-title { font-weight: 700; margin-bottom: 4px; color: #111827; }

        .card-footer {
            padding: 10px 15px 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-box { text-align: center; width: 100px; }
        .signature-line { border-top: 1px solid #111827; margin-bottom: 4px; }
        .signature-text { font-size: 7.5pt; color: #4b5563; font-weight: 600; }

        .qr-section { text-align: right; }
        .qr-text { font-size: 6pt; color: #6b7280; margin-top: 2px; }

        @media print {
            body { background: #ffffff; }
            .action-bar { display: none !important; }
            .page-container { margin: 0; box-shadow: none; padding: 0; margin-top: 5mm; page-break-after: always; }
        }
    </style>
</head>
<body>

<div class="action-bar d-print-none">
    <a href="{{ url()->previous() }}" class="btn">
        &larr; Back
    </a>
    <button onclick="window.print()" class="btn btn-primary">
        &#128438; Print Admit Cards
    </button>
</div>

@php
    // Chunking to handle A4 pages properly
    $cardsPerPage = $layout === 'a4_multiple' ? 4 : 2;
    $chunks = $students->chunk($cardsPerPage);
@endphp

@foreach($chunks as $chunk)
<div class="page-container">
    @foreach($chunk as $student)
        @php
            $enrollment = $student->currentEnrollment;
            $token = 'admit_' . $exam->id . '_' . $student->id . '_' . substr(md5($exam->id . $student->id . config('app.key')), 0, 8);
            $verifyUrl = route('verification.show', ['token' => $token]);
        @endphp
        
        <div class="admit-card">
            <div class="card-header">
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                    @if($setting && $setting->logo)
                        <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" alt="Logo" style="height: 35px;">
                    @endif
                    <div>
                        <div class="school-name">{{ $setting->title ?? 'BLESSED SACRAMENT' }}</div>
                        <div class="school-address">{{ $setting->address ?? 'Lalitpur, Nepal' }}</div>
                    </div>
                </div>
                <div class="badge">ADMIT CARD</div>
                <div class="exam-name">{{ $exam->name }} ({{ $exam->academicYear?->name }})</div>
            </div>
            
            <div class="card-body">
                <div class="student-section">
                    <div class="photo-box">
                        @if($student->photo)
                            <img src="{{ asset('uploads/students/' . $student->photo) }}">
                        @else
                            <div class="photo-placeholder">&#128100;</div>
                        @endif
                    </div>
                    <div style="flex-grow: 1;">
                        <table class="info-table">
                            <tr>
                                <td class="label">Name:</td>
                                <td class="value">{{ mb_strtoupper($student->full_name) }}</td>
                            </tr>
                            <tr>
                                <td class="label">Class/Sec:</td>
                                <td class="value">{{ $enrollment?->academicClass?->name }} ({{ $enrollment?->section?->name }})</td>
                            </tr>
                            <tr>
                                <td class="label">Roll No:</td>
                                <td class="value" style="color: #2563eb; font-size: 11pt;">{{ $enrollment?->roll_number ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Adm No:</td>
                                <td class="value">{{ $student->admission_no }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="instructions">
                    <div class="instructions-title">Important Instructions:</div>
                    <ul style="padding-left: 15px; margin-bottom: 0;">
                        <li>This card must be presented on each day of the examination.</li>
                        <li>Students must arrive 15 minutes before the exam starts.</li>
                        <li>No electronic gadgets are allowed inside the hall.</li>
                    </ul>
                </div>
            </div>
            
            <div class="card-footer">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-text">Class Teacher</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-text">Principal / Controller</div>
                </div>
                <div class="qr-section">
                    {!! \App\Services\QrCodeService::svg($verifyUrl, 45) !!}
                    <div class="qr-text">Scan to Verify</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endforeach

</body>
</html>
