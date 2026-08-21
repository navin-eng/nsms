@extends('frontend.layout.master')
@section('frontend-content')

{{-- ===== PAGE HERO ===== --}}
<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>Online Admission</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Admission
            </nav>
        </div>
    </div>
</div>

<style>
    .admission-wrapper {
        padding: 70px 0 90px;
        background: var(--bg-base, #f8fafc);
    }
    .admission-closed-card {
        text-align: center;
        background: #fff;
        border-radius: 16px;
        padding: 60px 40px;
        max-width: 560px;
        margin: 0 auto;
        box-shadow: 0 8px 40px rgba(0,0,0,.08);
    }
    .admission-closed-icon {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: #fff3cd;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 42px;
    }
    .admission-form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,.08);
        overflow: hidden;
    }
    .admission-form-header {
        background: linear-gradient(135deg, var(--primary, #2d6a4f) 0%, var(--primary-dark, #1a472a) 100%);
        padding: 36px 40px 32px;
        color: #fff;
    }
    .admission-form-header h2 { font-size: 1.6rem; font-weight: 700; margin-bottom: 6px; }
    .admission-form-header p { opacity: .85; margin: 0; font-size: .95rem; }
    .admission-form-body { padding: 40px; }
    .step-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--primary, #2d6a4f);
        color: #fff;
        border-radius: 6px;
        padding: 5px 14px;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        margin-bottom: 16px;
    }
    .section-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 36px 0 22px;
    }
    .section-divider .label {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--primary, #2d6a4f);
        white-space: nowrap;
    }
    .section-divider hr { flex: 1; border-color: #e2e8f0; margin: 0; }
    .form-label { font-weight: 600; font-size: .88rem; color: #374151; margin-bottom: 5px; }
    .form-label span.required { color: #ef4444; }
    .form-control, .form-select {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: .92rem;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary, #2d6a4f);
        box-shadow: 0 0 0 3px rgba(45,106,79,.12);
    }
    .btn-submit-admission {
        background: linear-gradient(135deg, var(--primary, #2d6a4f), var(--primary-dark, #1a472a));
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        padding: 14px 40px;
        letter-spacing: .03em;
        transition: transform .2s, box-shadow .2s;
        cursor: pointer;
    }
    .btn-submit-admission:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(45,106,79,.35);
    }
    .side-info-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(0,0,0,.06);
        padding: 28px 24px;
        margin-bottom: 24px;
    }
    .side-info-card h6 { font-weight: 700; color: var(--primary, #2d6a4f); margin-bottom: 14px; font-size: .95rem; }
    .info-step {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    .info-step-number {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--primary, #2d6a4f);
        color: #fff;
        font-size: .75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .info-step-text h6 { font-size: .85rem; font-weight: 600; margin-bottom: 2px; color: #1f2937; }
    .info-step-text p { font-size: .8rem; color: #6b7280; margin: 0; }
    .alert-success-custom {
        background: #f0fdf4;
        border: 1.5px solid #4ade80;
        color: #166534;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 28px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .alert-success-custom i { font-size: 1.3rem; margin-top: 1px; }
    .alert-danger-custom {
        background: #fef2f2;
        border: 1.5px solid #fca5a5;
        color: #991b1b;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 28px;
    }
</style>

<div class="admission-wrapper">
    <div class="container">

        @if(!($settings->enable_online_admission ?? false))
        {{-- ===== ADMISSIONS CLOSED ===== --}}
        <div class="admission-closed-card" data-aos="fade-up">
            <div class="admission-closed-icon">🔒</div>
            <h3 class="fw-bold mb-2" style="color:#1f2937;">Online Admissions are Closed</h3>
            <p class="text-muted mb-4" style="font-size:.95rem;">
                We are not currently accepting online admission applications. 
                Admission season typically opens at the beginning of the academic year.
                Please check back later or contact us for more information.
            </p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('contact') }}" class="btn" style="background:var(--primary,#2d6a4f);color:#fff;border-radius:8px;padding:10px 24px;font-weight:600;">
                    <i class="fas fa-phone-alt me-2"></i>Contact Us
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:10px 24px;">
                    <i class="fas fa-home me-2"></i>Back to Home
                </a>
            </div>
        </div>

        @else
        {{-- ===== ADMISSIONS OPEN ===== --}}
        <div class="row g-4 align-items-start">

            {{-- ===== MAIN FORM ===== --}}
            <div class="col-lg-8" data-aos="fade-right">
                <div class="admission-form-card">
                    <div class="admission-form-header">
                        <div class="step-badge"><i class="fas fa-graduation-cap"></i> Admission Open</div>
                        <h2>Student Admission Application</h2>
                        <p>Complete all fields below. Our admissions team will review and contact you shortly.</p>
                    </div>
                    <div class="admission-form-body">

                        @if(session('success'))
                        <div class="alert-success-custom">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Application Submitted!</strong><br>
                                {{ session('success') }}
                            </div>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert-danger-custom">
                            <strong>Error:</strong> {{ session('error') }}
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert-danger-custom">
                            <strong>Please fix the following:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('admission.submit') }}" method="POST">
                            @csrf

                            {{-- STUDENT INFO --}}
                            <div class="section-divider"><span class="label">👤 Student Information</span><hr></div>
                            <div class="row g-3 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">First Name <span class="required">*</span></label>
                                    <input type="text" name="first_name" class="form-control" placeholder="e.g. Ram" required value="{{ old('first_name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name <span class="required">*</span></label>
                                    <input type="text" name="last_name" class="form-control" placeholder="e.g. Sharma" required value="{{ old('last_name') }}">
                                </div>
                            </div>
                            <div class="row g-3 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth <span class="required">*</span></label>
                                    <input type="date" name="dob" class="form-control" required value="{{ old('dob') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender <span class="required">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender')=='Male' ? 'selected':'' }}>Male</option>
                                        <option value="Female" {{ old('gender')=='Female' ? 'selected':'' }}>Female</option>
                                        <option value="Other" {{ old('gender')=='Other' ? 'selected':'' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                            {{-- ACADEMIC INFO --}}
                            <div class="section-divider"><span class="label">🎓 Academic Details</span><hr></div>
                            <div class="row g-3 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Academic Year <span class="required">*</span></label>
                                    <select name="academic_year_id" class="form-select" required>
                                        <option value="">Select Year</option>
                                        @foreach($years as $year)
                                        <option value="{{ $year->id }}" {{ old('academic_year_id')==$year->id ? 'selected':'' }}>{{ $year->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Class Applying For <span class="required">*</span></label>
                                    <select name="academic_class_id" class="form-select" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('academic_class_id')==$class->id ? 'selected':'' }}>{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Previous School / Institution</label>
                                <input type="text" name="previous_school" class="form-control" placeholder="Leave blank if not applicable" value="{{ old('previous_school') }}">
                            </div>

                            {{-- PARENT INFO --}}
                            <div class="section-divider"><span class="label">👨‍👩‍👧 Parent / Guardian</span><hr></div>
                            <div class="row g-3 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Father's Name</label>
                                    <input type="text" name="father_name" class="form-control" placeholder="Father's full name" value="{{ old('father_name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mother's Name</label>
                                    <input type="text" name="mother_name" class="form-control" placeholder="Mother's full name" value="{{ old('mother_name') }}">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Contact Number <span class="required">*</span></label>
                                <input type="text" name="contact_number" class="form-control" placeholder="e.g. 98XXXXXXXX" required value="{{ old('contact_number') }}">
                            </div>

                            <div class="d-flex align-items-center gap-3 pt-2 border-top">
                                <button type="submit" class="btn-submit-admission">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Application
                                </button>
                                <small class="text-muted">By submitting, you agree that the information provided is accurate.</small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ===== SIDEBAR INFO ===== --}}
            <div class="col-lg-4" data-aos="fade-left" data-aos-delay="100">
                <div class="side-info-card">
                    <h6><i class="fas fa-list-check me-2"></i>Admission Process</h6>
                    <div class="info-step">
                        <div class="info-step-number">1</div>
                        <div class="info-step-text">
                            <h6>Submit Application</h6>
                            <p>Fill out and submit this online form.</p>
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="info-step-number">2</div>
                        <div class="info-step-text">
                            <h6>Review</h6>
                            <p>Our team reviews your application within 2–3 working days.</p>
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="info-step-number">3</div>
                        <div class="info-step-text">
                            <h6>Contact & Interview</h6>
                            <p>We'll call you on the provided number to confirm details.</p>
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="info-step-number">4</div>
                        <div class="info-step-text">
                            <h6>Enrollment</h6>
                            <p>Bring required documents and complete the enrollment process.</p>
                        </div>
                    </div>
                </div>

                <div class="side-info-card">
                    <h6><i class="fas fa-file-alt me-2"></i>Required Documents</h6>
                    <ul style="padding-left:1.2rem;margin:0;font-size:.88rem;color:#374151;line-height:2;">
                        <li>Birth Certificate (Photocopy)</li>
                        <li>Previous School's Transfer Certificate</li>
                        <li>Previous Academic Mark Sheet</li>
                        <li>4 Passport Size Photos</li>
                        <li>Citizenship / NID (Parent)</li>
                    </ul>
                </div>

                <div class="side-info-card" style="background:linear-gradient(135deg,var(--primary,#2d6a4f),var(--primary-dark,#1a472a));color:#fff;">
                    <h6 style="color:#fff;"><i class="fas fa-headset me-2"></i>Need Help?</h6>
                    <p style="font-size:.85rem;opacity:.9;margin-bottom:10px;">Our admissions team is happy to answer your questions.</p>
                    @php $s = $settings; @endphp
                    @if($s->contact_phone)
                    <div style="font-size:.9rem;font-weight:600;margin-bottom:6px;"><i class="fas fa-phone me-2"></i>{{ $s->contact_phone }}</div>
                    @endif
                    @if($s->contact_email)
                    <div style="font-size:.9rem;"><i class="fas fa-envelope me-2"></i>{{ $s->contact_email }}</div>
                    @endif
                </div>
            </div>

        </div>
        @endif

    </div>
</div>

@endsection
