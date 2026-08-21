@extends('backend.pages.layout.master')
@section('title', 'Staff ID Cards')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Staff & Employee ID Cards</h5>
        <p class="text-muted small mb-0">Generate and print ID cards for teachers and administrative staff.</p>
    </div>
    @if($staffMembers->isNotEmpty())
        <div class="d-flex gap-2">
            <a href="{{ request()->fullUrlWithQuery(['print' => '1']) }}" target="_blank" class="btn btn-sm btn-primary">
                <i class="bi bi-printer me-1"></i>Print {{ $staffMembers->count() }} ID Cards
            </a>
        </div>
    @endif
</div>

{{-- Filter Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('sms.id-cards.staff') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Department</label>
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ request('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Card Layout</label>
                <select name="layout" class="form-select form-select-sm">
                    <option value="portrait" {{ $layout == 'portrait' ? 'selected' : '' }}>Portrait (Vertical)</option>
                    <option value="landscape" {{ $layout == 'landscape' ? 'selected' : '' }}>Landscape (Horizontal)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

@if($staffMembers->isNotEmpty())
    {{-- Cards Live Grid Preview --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Live Preview ({{ $staffMembers->count() }} Staff Cards)</h6>
        </div>
        <div class="card-body p-4 bg-light">
            <div class="row g-4 justify-content-center">
                @foreach($staffMembers as $staff)
                    <div class="col-auto">
                        @if($layout === 'portrait')
                            {{-- Portrait Card Preview --}}
                            <div class="id-card-portrait shadow-sm rounded-3 overflow-hidden bg-white border">
                                <div class="id-header text-center p-2 text-white" style="background: #111827;">
                                    @if($setting && $setting->logo)
                                        <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" alt="Logo" style="height: 24px; filter: brightness(0) invert(1);">
                                    @endif
                                    <div class="fw-bold text-uppercase" style="font-size: 8.5pt; letter-spacing: 0.5px;">{{ $setting->title ?? 'BLESSED SACRAMENT' }}</div>
                                    <div style="font-size: 6pt; opacity: 0.9; color:#fbbf24;">STAFF IDENTITY CARD</div>
                                </div>
                                <div class="id-body text-center p-3">
                                    <div class="avatar-box mb-2 mx-auto" style="width: 70px; height: 75px; border-radius: 8px; border: 2px solid #fbbf24; overflow: hidden; background:#e5e7eb;">
                                        @if($staff->photo)
                                            <img src="{{ asset('uploads/staff/' . $staff->photo) }}" style="width:100%; height:100%; object-fit:cover;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center h-100 text-muted fw-bold" style="font-size: 16pt;">
                                                {{ strtoupper(substr($staff->first_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 10pt;">{{ $staff->full_name }}</div>
                                    <div class="badge bg-warning text-dark px-2 py-0 mb-2" style="font-size: 7pt;">
                                        {{ $staff->designation?->name ?? 'Staff' }}
                                    </div>

                                    <table class="table table-sm table-borderless text-start mb-2" style="font-size: 7pt; line-height: 1.3;">
                                        <tr><td class="text-muted p-0" style="width:45%;">Emp ID:</td><td class="fw-bold text-dark p-0">{{ $staff->employee_id ?? '#' . $staff->id }}</td></tr>
                                        <tr><td class="text-muted p-0">Department:</td><td class="fw-bold text-dark p-0">{{ $staff->department?->name ?? '—' }}</td></tr>
                                        <tr><td class="text-muted p-0">Phone:</td><td class="fw-bold text-dark p-0">{{ $staff->phone ?? '—' }}</td></tr>
                                    </table>

                                    <div class="mt-auto pt-2 border-top text-start text-center">
                                        {!! \App\Services\QrCodeService::barcodeSvg($staff->employee_id ?? (string)$staff->id, 120, 26) !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Landscape Card Preview --}}
                            <div class="id-card-landscape shadow-sm rounded-3 overflow-hidden bg-white border d-flex flex-column justify-content-between">
                                <div class="id-header p-2 text-white d-flex justify-content-between align-items-center" style="background: #111827;">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($setting && $setting->logo)
                                            <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" alt="Logo" style="height: 20px; filter: brightness(0) invert(1);">
                                        @endif
                                        <div class="fw-bold text-uppercase" style="font-size: 8pt;">{{ $setting->title ?? 'BLESSED SACRAMENT' }}</div>
                                    </div>
                                    <span class="badge bg-warning text-dark" style="font-size: 6pt;">STAFF</span>
                                </div>
                                <div class="id-body p-2 d-flex gap-3 align-items-center">
                                    <div class="avatar-box" style="width: 65px; height: 75px; border-radius: 6px; border: 2px solid #fbbf24; overflow: hidden; background:#e5e7eb; flex-shrink: 0;">
                                        @if($staff->photo)
                                            <img src="{{ asset('uploads/staff/' . $staff->photo) }}" style="width:100%; height:100%; object-fit:cover;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center h-100 text-muted fw-bold" style="font-size: 14pt;">
                                                {{ strtoupper(substr($staff->first_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1" style="font-size: 7pt; line-height: 1.3;">
                                        <div class="fw-bold text-dark text-truncate" style="font-size: 9.5pt;">{{ $staff->full_name }}</div>
                                        <div class="text-warning-emphasis fw-semibold mb-1">{{ $staff->designation?->name ?? 'Staff' }}</div>
                                        <div><span class="text-muted">Emp ID:</span> <strong>{{ $staff->employee_id ?? '#' . $staff->id }}</strong></div>
                                        <div><span class="text-muted">Dept:</span> <strong>{{ $staff->department?->name ?? '—' }}</strong></div>
                                        <div><span class="text-muted">Phone:</span> <strong>{{ $staff->phone ?? '—' }}</strong></div>
                                    </div>
                                    <div class="flex-shrink-0 text-center border-left pl-2">
                                        {!! \App\Services\QrCodeService::barcodeSvg($staff->employee_id ?? (string)$staff->id, 70, 40) !!}
                                    </div>
                                </div>
                                <div class="id-footer px-2 py-1 bg-light border-top d-flex justify-content-between align-items-center" style="font-size: 6pt;">
                                    <span class="text-muted">{{ $setting->address ?? 'Lalitpur, Nepal' }}</span>
                                    <span class="fw-bold">Auth. Sign</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-person-badge fs-1 d-block mb-3 opacity-50"></i>
            <h6>No Staff Found</h6>
            <p class="small mb-0">Select a department or check active staff records.</p>
        </div>
    </div>
@endif

<style>
.id-card-portrait {
    width: 215px;
    height: 330px;
    display: flex;
    flex-direction: column;
}
.id-card-landscape {
    width: 325px;
    height: 205px;
}
</style>
@endsection
