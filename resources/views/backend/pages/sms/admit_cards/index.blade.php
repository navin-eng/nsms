@extends('backend.pages.layout.master')
@section('title', 'Generate Admit Cards')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Admit Cards</h5>
        <p class="text-muted small mb-0">Generate and print examination admit cards with QR verification.</p>
    </div>
    @if(isset($students) && $students->isNotEmpty())
        <div class="d-flex gap-2">
            <a href="{{ request()->fullUrlWithQuery(['print' => '1']) }}" target="_blank" class="btn btn-sm btn-primary">
                <i class="bi bi-printer me-1"></i>Print {{ $students->count() }} Admit Cards
            </a>
        </div>
    @endif
</div>

{{-- Filter Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('sms.admit-cards.index') }}" method="GET" class="row g-3 align-items-end" id="admitCardForm">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Examination <span class="text-danger">*</span></label>
                <select name="exam_id" class="form-select form-select-sm" required>
                    <option value="">Select Exam</option>
                    @foreach($exams as $e)
                        <option value="{{ $e->id }}" {{ request('exam_id') == $e->id ? 'selected' : '' }}>{{ $e->name }} ({{ $e->academicYear?->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Class <span class="text-danger">*</span></label>
                <select name="academic_class_id" class="form-select form-select-sm" required onchange="this.form.submit()">
                    <option value="">Select Class</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('academic_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Section</label>
                <select name="section_id" class="form-select form-select-sm">
                    <option value="">All Sections</option>
                    @if(isset($sections))
                        @foreach($sections as $s)
                            <option value="{{ $s->id }}" {{ request('section_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Template Layout</label>
                <select name="layout" class="form-select form-select-sm">
                    <option value="a4_multiple" {{ $layout == 'a4_multiple' ? 'selected' : '' }}>Multiple per A4 Sheet (Standard)</option>
                    <option value="a4_single" {{ $layout == 'a4_single' ? 'selected' : '' }}>Single per A4 Sheet (Detailed)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-gear me-1"></i>Generate</button>
            </div>
        </form>
    </div>
</div>

@if(isset($students))
    @if($students->isNotEmpty() && isset($exam))
        {{-- Preview Section --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Live Preview ({{ $students->count() }} Students)</h6>
            </div>
            <div class="card-body p-4 bg-light">
                <div class="row g-4 justify-content-center">
                    @foreach($students->take(4) as $student) {{-- Show max 4 in preview --}}
                        <div class="col-md-6 col-lg-5">
                            <div class="card border border-2 border-primary border-opacity-25 shadow-sm h-100">
                                <div class="card-header text-center bg-white border-bottom-0 pt-3 pb-0">
                                    <h6 class="fw-bold mb-0 text-uppercase text-primary">{{ $setting->title ?? 'BLESSED SACRAMENT' }}</h6>
                                    <p class="small text-muted mb-2">{{ $setting->address ?? 'Lalitpur, Nepal' }}</p>
                                    <div class="badge bg-primary fs-6 mb-2 py-2 px-4 shadow-sm rounded-pill">ADMIT CARD</div>
                                    <h6 class="fw-bold text-dark">{{ $exam->name }}</h6>
                                </div>
                                <div class="card-body px-4 py-3">
                                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom border-light">
                                        <div style="width: 80px; height: 90px; border: 1px solid #dee2e6; padding: 3px; background: #fff;" class="flex-shrink-0 shadow-sm">
                                            @if($student->photo)
                                                <img src="{{ asset('uploads/students/' . $student->photo) }}" class="w-100 h-100" style="object-fit: cover;">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted">
                                                    <i class="bi bi-person fs-1"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <table class="table table-sm table-borderless mb-0">
                                                <tr><td class="text-muted fw-medium p-0" style="width:30%">Name:</td><td class="fw-bold p-0">{{ $student->full_name }}</td></tr>
                                                <tr><td class="text-muted fw-medium p-0">Class:</td><td class="fw-bold p-0">{{ $student->currentEnrollment?->academicClass?->name }} ({{ $student->currentEnrollment?->section?->name }})</td></tr>
                                                <tr><td class="text-muted fw-medium p-0">Roll No:</td><td class="fw-bold p-0 text-primary">{{ $student->currentEnrollment?->roll_number ?? '—' }}</td></tr>
                                                <tr><td class="text-muted fw-medium p-0">Adm No:</td><td class="fw-bold p-0">{{ $student->admission_no }}</td></tr>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-end mt-4 pt-2">
                                        <div class="text-center" style="width: 120px;">
                                            <div class="border-bottom border-dark mb-1"></div>
                                            <small class="text-muted fw-medium" style="font-size: 0.75rem;">Principal Sign</small>
                                        </div>
                                        <div class="text-end">
                                            @php
                                                $token = 'admit_' . $exam->id . '_' . $student->id . '_' . substr(md5($exam->id . $student->id . config('app.key')), 0, 8);
                                                $verifyUrl = route('verification.show', ['token' => $token]);
                                            @endphp
                                            {!! \App\Services\QrCodeService::svg($verifyUrl, 50) !!}
                                            <div style="font-size: 0.6rem; color: #6c757d; margin-top:2px;">Scan to Verify</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($students->count() > 4)
                    <div class="text-center mt-4">
                        <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i>Showing first 4 admit cards. Click Print to view all {{ $students->count() }}.</p>
                    </div>
                @endif
            </div>
        </div>
    @elseif(!isset($exam))
        {{-- Selected class but no exam --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-exclamation-circle fs-1 d-block mb-3 opacity-50"></i>
                <h6>Please select an Examination</h6>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-person-x fs-1 d-block mb-3 opacity-50"></i>
                <h6>No Students Found</h6>
                <p class="small mb-0">No students are enrolled in the selected criteria.</p>
            </div>
        </div>
    @endif
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted bg-light border border-dashed rounded">
            <i class="bi bi-card-heading fs-1 d-block mb-3 opacity-25 text-primary"></i>
            <h6 class="fw-bold">Generate Admit Cards</h6>
            <p class="small mb-0 mx-auto" style="max-width: 400px;">Select an examination and class above to generate admit cards. All generated cards include secure, verifiable QR codes.</p>
        </div>
    </div>
@endif

@endsection
