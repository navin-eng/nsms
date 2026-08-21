<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Staff ID Cards</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f3f4f6; color: #111827; }
        .action-bar { max-width: 900px; margin: 20px auto; display: flex; justify-content: space-between; align-items: center; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; font-size: 14px; font-weight: 600; border-radius: 9999px; cursor: pointer; text-decoration: none; border: 1px solid #d1d5db; background: #ffffff; color: #374151; }
        .btn-primary { background: #2563eb; color: #ffffff; border-color: #2563eb; }
        
        .page-container {
            width: 210mm; /* A4 */
            margin: 0 auto;
            background: #ffffff;
            padding: 10mm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 20mm;
            display: grid;
            gap: 5mm;
            justify-content: center;
        }
        
        @if($layout === 'portrait')
            .page-container { grid-template-columns: repeat(3, 1fr); }
        @else
            .page-container { grid-template-columns: repeat(2, 1fr); }
        @endif

        .id-card {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
            position: relative;
        }
        
        .id-card-portrait { width: 54mm; height: 85.6mm; display: flex; flex-direction: column; }
        .id-card-landscape { width: 85.6mm; height: 54mm; display: flex; flex-direction: column; justify-content: space-between; }

        .id-header {
            text-align: center;
            padding: 4px 2px;
            background: #111827; /* Dark elegant header for staff */
            color: white;
            border-bottom: 3px solid #fbbf24;
        }
        .school-name { font-weight: 700; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.5px; }
        .card-type { font-size: 5pt; letter-spacing: 1px; color: #fbbf24; }
        
        .id-body { padding: 6px; text-align: center; flex-grow: 1; display: flex; flex-direction: column; }
        .avatar-box { background: #e5e7eb; border: 2px solid #fbbf24; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .avatar-box img { width: 100%; height: 100%; object-fit: cover; }
        
        .staff-name { font-weight: 700; font-size: 9.5pt; color: #111827; margin: 4px 0 2px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .id-badge { display: inline-block; padding: 1px 6px; border-radius: 4px; font-size: 6.5pt; font-weight: 600; margin-bottom: 4px; background: #fef3c7; color: #b45309; }
        
        .info-table { width: 100%; text-align: left; font-size: 6pt; line-height: 1.3; border-collapse: collapse; margin-top: auto; }
        .info-table td { padding: 0.5px 0; }
        .info-label { color: #4b5563; width: 45%; }
        .info-value { color: #111827; font-weight: 600; }
        
        .id-footer { border-top: 1px solid #e5e7eb; padding-top: 4px; margin-top: 4px; }
        
        /* Layout Specific Adjustments */
        .portrait-body .avatar-box { width: 22mm; height: 26mm; margin: 0 auto; border-radius: 4px; }
        
        .landscape-body { flex-direction: row; text-align: left; gap: 8px; align-items: flex-start; }
        .landscape-body .avatar-box { width: 20mm; height: 24mm; border-radius: 4px; flex-shrink: 0; }
        .landscape-info { flex-grow: 1; display: flex; flex-direction: column; }

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
        &#128438; Print Cards
    </button>
</div>

@php
    $cardsPerPage = $layout === 'portrait' ? 9 : 8;
    $chunks = $staffMembers->chunk($cardsPerPage);
@endphp

@foreach($chunks as $chunk)
<div class="page-container">
    @foreach($chunk as $staff)
        @if($layout === 'portrait')
            <div class="id-card id-card-portrait">
                <div class="id-header">
                    @if($setting && $setting->logo)
                        <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" alt="Logo" style="height: 18px; filter: brightness(0) invert(1);">
                    @endif
                    <div class="school-name">{{ $setting->title ?? 'BLESSED SACRAMENT' }}</div>
                    <div class="card-type">STAFF IDENTITY CARD</div>
                </div>
                <div class="id-body portrait-body">
                    <div class="avatar-box">
                        @if($staff->photo)
                            <img src="{{ asset('uploads/staff/' . $staff->photo) }}">
                        @else
                            <div style="font-size: 14pt; color: #9ca3af; font-weight: bold;">{{ strtoupper(substr($staff->first_name, 0, 1)) }}</div>
                        @endif
                    </div>
                    <div class="staff-name">{{ $staff->full_name }}</div>
                    <div><span class="id-badge">{{ $staff->designation?->name ?? 'Staff' }}</span></div>
                    
                    <table class="info-table">
                        <tr><td class="info-label">Emp ID:</td><td class="info-value">{{ $staff->employee_id ?? '#' . $staff->id }}</td></tr>
                        <tr><td class="info-label">Dept:</td><td class="info-value">{{ $staff->department?->name ?? '—' }}</td></tr>
                        <tr><td class="info-label">Phone:</td><td class="info-value">{{ $staff->phone ?? '—' }}</td></tr>
                    </table>
                    
                    <div class="id-footer">
                        <div style="text-align: center; margin-bottom: 2px;">
                            {!! \App\Services\QrCodeService::barcodeSvg($staff->employee_id ?? (string)$staff->id, 80, 20) !!}
                        </div>
                        <div style="font-size:5pt; text-align:center; color:#6b7280; width:100%;">
                            {{ $setting->address ?? 'Lalitpur, Nepal' }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Landscape --}}
            <div class="id-card id-card-landscape">
                <div class="id-header" style="display:flex; justify-content:space-between; align-items:center; padding: 4px 6px;">
                    <div style="display:flex; align-items:center; gap: 4px;">
                        @if($setting && $setting->logo)
                            <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" alt="Logo" style="height: 16px; filter: brightness(0) invert(1);">
                        @endif
                        <div class="school-name">{{ $setting->title ?? 'BLESSED SACRAMENT' }}</div>
                    </div>
                    <div class="card-type" style="font-size: 6pt; font-weight:bold;">STAFF</div>
                </div>
                
                <div class="id-body landscape-body">
                    <div class="avatar-box">
                        @if($staff->photo)
                            <img src="{{ asset('uploads/staff/' . $staff->photo) }}">
                        @else
                            <div style="font-size: 14pt; color: #9ca3af; font-weight: bold;">{{ strtoupper(substr($staff->first_name, 0, 1)) }}</div>
                        @endif
                    </div>
                    
                    <div class="landscape-info">
                        <div class="staff-name" style="font-size: 10pt; margin-top:0;">{{ $staff->full_name }}</div>
                        <div style="margin-bottom: 2px;"><span class="id-badge" style="font-size: 7pt;">{{ $staff->designation?->name ?? 'Staff' }}</span></div>
                        
                        <table class="info-table" style="font-size: 6.5pt;">
                            <tr><td class="info-label" style="width:30%;">Emp ID:</td><td class="info-value">{{ $staff->employee_id ?? '#' . $staff->id }}</td></tr>
                            <tr><td class="info-label">Dept:</td><td class="info-value">{{ $staff->department?->name ?? '—' }}</td></tr>
                            <tr><td class="info-label">Phone:</td><td class="info-value">{{ $staff->phone ?? '—' }}</td></tr>
                        </table>
                    </div>
                    
                    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; border-left: 1px solid #e5e7eb; padding-left: 6px;">
                        {!! \App\Services\QrCodeService::barcodeSvg($staff->employee_id ?? (string)$staff->id, 50, 30) !!}
                    </div>
                </div>
                
                <div class="id-footer" style="padding: 2px 6px; font-size: 5.5pt; background:#f9fafb; display: flex; justify-content: space-between; align-items: center;">
                    <div style="color:#4b5563;">{{ $setting->address ?? 'Lalitpur, Nepal' }}</div>
                    <div style="font-weight:600; text-decoration:overline;">Auth. Sign</div>
                </div>
            </div>
        @endif
    @endforeach
</div>
@endforeach

</body>
</html>
