<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Student ID Cards</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            color: #111827;
        }

        .action-bar {
            max-width: 900px;
            margin: 20px auto;
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

        .page-container {
            @if(isset($printFormat) && $printFormat === 'id_printer')
                /* ID Printer layout: exactly one CR80 card per page */
                @if($layout === 'portrait')
                    width: 54mm;
                    height: 85.6mm;
                @else
                    width: 85.6mm;
                    height: 54mm;
                @endif
                padding: 0;
                margin: 0 auto;
                background: #ffffff;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                margin-bottom: 20mm;
                display: flex;
            @else
                /* A4 Grid Layout */
                width: 210mm;
                margin: 0 auto;
                background: #ffffff;
                padding: 10mm;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                margin-bottom: 20mm;
                display: grid;
                gap: 5mm;
            @endif
        }

        /* Grid Layout */
        @if($layout === 'portrait')
            .page-container {
                grid-template-columns: repeat(3, 1fr);
            }

        @else .page-container {
                grid-template-columns: repeat(2, 1fr);
            }

        @endif

        /* Card Styles */
        .id-card {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
            position: relative;
        }

        .id-card::before,
        .id-card::after {
            /* Cut marks */
            content: '';
            position: absolute;
        }

        .id-card-portrait {
            width: 54mm;
            height: 85.6mm;
            display: flex;
            flex-direction: column;
        }

        .id-card-landscape {
            width: 85.6mm;
            height: 54mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Themes */
        .theme-modern .id-header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
        }

        .theme-classic .id-header {
            background: #1e3a8a;
            color: white;
            border-bottom: 3px solid #fbbf24;
        }

        .theme-elegant .id-header {
            background: #831843;
            color: white;
        }

        .theme-modern .id-badge {
            background: #e0e7ff;
            color: #4f46e5;
            border: 1px solid #c7d2fe;
        }

        .theme-classic .id-badge {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .theme-elegant .id-badge {
            background: #fce7f3;
            color: #be185d;
            border: 1px solid #fbcfe8;
        }

        /* General Inner Elements */
        .id-header {
            text-align: center;
            padding: 4px 2px;
        }

        .school-name {
            font-weight: 700;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-type {
            font-size: 5pt;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        .id-body {
            padding: 6px;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .avatar-box {
            background: #e5e7eb;
            border: 1px solid #9ca3af;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-name {
            font-weight: 700;
            font-size: 9pt;
            color: #111827;
            margin: 4px 0 2px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .id-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 6.5pt;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-table {
            width: 100%;
            text-align: left;
            font-size: 6pt;
            line-height: 1.3;
            border-collapse: collapse;
            margin-top: auto;
        }

        .info-table td {
            padding: 0.5px 0;
        }

        .info-label {
            color: #4b5563;
            width: 40%;
        }

        .info-value {
            color: #111827;
            font-weight: 600;
        }

        .id-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 4px;
            margin-top: 4px;
        }

        /* Layout Specific Adjustments */
        .portrait-body .avatar-box {
            width: 22mm;
            height: 26mm;
            margin: 0 auto;
            border-radius: 4px;
        }

        .portrait-footer {
            flex-direction: column;
            gap: 4px;
        }

        .landscape-body {
            flex-direction: row;
            text-align: left;
            gap: 8px;
            align-items: flex-start;
        }

        .landscape-body .avatar-box {
            width: 20mm;
            height: 24mm;
            border-radius: 4px;
            flex-shrink: 0;
        }

        .landscape-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        @media print {
            @if(isset($printFormat) && $printFormat === 'id_printer')
                @if($layout === 'portrait')
                    @page { size: 54mm 85.6mm; margin: 0; }
                @else
                    @page { size: 85.6mm 54mm; margin: 0; }
                @endif
                
                body {
                    background: #ffffff;
                    margin: 0;
                    padding: 0;
                }
                
                .page-container {
                    margin: 0;
                    box-shadow: none;
                    padding: 0;
                    border: none;
                    page-break-after: always;
                    page-break-inside: avoid;
                }
                
                .id-card {
                    border: none;
                }
            @else
                @page { size: A4; margin: 5mm; }
                
                body {
                    background: #ffffff;
                }

                .page-container {
                    margin: 0;
                    box-shadow: none;
                    padding: 0;
                    margin-top: 5mm;
                    page-break-after: always;
                }
            @endif

            .action-bar {
                display: none !important;
            }
        }
    </style>
</head>

<body class="theme-{{ $template }}">

    <div class="action-bar d-print-none">
        <a href="{{ url()->previous() }}" class="btn">
            &larr; Back
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            &#128438; Print Cards
        </button>
    </div>

    {{-- Paginate manually --}}
    @php
        if (isset($printFormat) && $printFormat === 'id_printer') {
            $cardsPerPage = 1;
        } else {
            $cardsPerPage = $layout === 'portrait' ? 9 : 8;
        }
        $chunks = $students->chunk($cardsPerPage);
    @endphp

    @foreach($chunks as $chunk)
        <div class="page-container">
            @foreach($chunk as $st)
                @php
                    $verifyUrl = route('verification.show', ['token' => 'id_student_' . $st->id . '_' . substr(md5($st->id . config('app.key')), 0, 8)]);
                    $enrollment = $st->currentEnrollment;
                @endphp

                @if($layout === 'portrait')
                    <div class="id-card id-card-portrait">
                        <div class="id-header">
                            @if($setting && $setting->logo)
                                <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" alt="Logo"
                                    style="height: 18px; filter: brightness(0) invert(1);">
                            @endif
                            <div class="school-name">{{ $setting->title ?? 'BLESSED SACRAMENT' }}</div>
                            <div class="card-type">STUDENT IDENTITY CARD</div>
                        </div>
                        <div class="id-body portrait-body">
                            <div class="avatar-box">
                                @if($st->photo)
                                    <img src="{{ asset('uploads/students/' . $st->photo) }}">
                                @else
                                    <div style="font-size: 14pt; color: #9ca3af; font-weight: bold;">
                                        {{ strtoupper(substr($st->first_name, 0, 1)) }}</div>
                                @endif
                            </div>
                            <div class="student-name">{{ $st->full_name }}</div>
                            <div><span class="id-badge">{{ $enrollment?->academicClass?->name ?? 'Class' }} -
                                    {{ $enrollment?->section?->name ?? 'Sec' }}</span></div>

                            <table class="info-table">
                                <tr>
                                    <td class="info-label">Adm No:</td>
                                    <td class="info-value">{{ $st->admission_no ?? '#' . $st->id }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Roll No:</td>
                                    <td class="info-value">{{ $enrollment?->roll_number ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">D.O.B:</td>
                                    <td class="info-value">
                                        {{ $st->date_of_birth ? \Carbon\Carbon::parse($st->date_of_birth)->format('d-m-Y') : '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="info-label">Blood:</td>
                                    <td class="info-value" style="color:#dc2626;">{{ $st->blood_group ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Phone:</td>
                                    <td class="info-value">{{ $st->phone ?? $st->guardian?->guardian_phone ?? '—' }}</td>
                                </tr>
                            </table>

                            <div class="id-footer portrait-footer">
                                <div style="display:flex; justify-content:space-between; width:100%; align-items:center;">
                                    {!! \App\Services\QrCodeService::barcodeSvg($st->admission_no ?? (string) $st->id, 75, 18) !!}
                                    {!! \App\Services\QrCodeService::svg($verifyUrl, 26) !!}
                                </div>
                                <div
                                    style="font-size:5pt; text-align:center; color:#6b7280; margin-top:2px; border-top:1px solid #e5e7eb; padding-top:2px; width:100%;">
                                    {{ $setting->address ?? 'Lalitpur, Nepal' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Landscape --}}
                    <div class="id-card id-card-landscape">
                        <div class="id-header"
                            style="display:flex; justify-content:space-between; align-items:center; padding: 4px 6px;">
                            <div style="display:flex; align-items:center; gap: 4px;">
                                @if($setting && $setting->logo)
                                    <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" alt="Logo"
                                        style="height: 16px; filter: brightness(0) invert(1);">
                                @endif
                                <div class="school-name">{{ $setting->title ?? 'BLESSED SACRAMENT' }}</div>
                            </div>
                            <div
                                style="font-size: 6pt; font-weight:bold; background:rgba(255,255,255,0.9); color:#111827; padding:1px 4px; border-radius:2px;">
                                STUDENT</div>
                        </div>

                        <div class="id-body landscape-body">
                            <div class="avatar-box">
                                @if($st->photo)
                                    <img src="{{ asset('uploads/students/' . $st->photo) }}">
                                @else
                                    <div style="font-size: 14pt; color: #9ca3af; font-weight: bold;">
                                        {{ strtoupper(substr($st->first_name, 0, 1)) }}</div>
                                @endif
                            </div>

                            <div class="landscape-info">
                                <div class="student-name" style="font-size: 10pt; margin-top:0;">{{ $st->full_name }}</div>
                                <div style="margin-bottom: 2px;"><span class="id-badge"
                                        style="font-size: 7pt;">{{ $enrollment?->academicClass?->name ?? 'Class' }}
                                        ({{ $enrollment?->section?->name ?? 'Sec' }})</span></div>

                                <table class="info-table" style="font-size: 6.5pt;">
                                    <tr>
                                        <td class="info-label" style="width:30%;">Adm No:</td>
                                        <td class="info-value">{{ $st->admission_no ?? '#' . $st->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">D.O.B:</td>
                                        <td class="info-value">
                                            {{ $st->date_of_birth ? \Carbon\Carbon::parse($st->date_of_birth)->format('d-m-Y') : '—' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Blood:</td>
                                        <td class="info-value" style="color:#dc2626;">{{ $st->blood_group ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Phone:</td>
                                        <td class="info-value">{{ $st->phone ?? $st->guardian?->guardian_phone ?? '—' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div
                                style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; border-left: 1px solid #e5e7eb; padding-left: 6px;">
                                {!! \App\Services\QrCodeService::svg($verifyUrl, 32) !!}
                                <div style="font-size: 4.5pt; color: #6b7280; margin-top:2px;">VERIFY</div>
                            </div>
                        </div>

                        <div class="id-footer" style="padding: 2px 6px; font-size: 5.5pt; background:#f9fafb;">
                            <div style="color:#4b5563;">{{ $setting->address ?? 'Lalitpur, Nepal' }}</div>
                            <div style="font-weight:600; text-decoration:overline; margin-top:4px;">Principal Sign</div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach

</body>

</html>