@extends('frontend.layout.master')
@section('frontend-content')

{{-- Hero --}}
<div class="detail-hero">
    <img src="{{ asset($notice->image) }}" alt="{{ $notice->title }}">
    <div class="overlay"></div>
    <div class="dh-title">
        <h1>{{ $notice->title }}</h1>
    </div>
</div>

{{-- Breadcrumb --}}
<div style="background:var(--green-pale);padding:10px 0;border-bottom:1px solid var(--border);">
    <div class="container">
        <small style="color:var(--text-muted);">
            <a href="{{ route('home') }}" style="color:var(--green-dark);font-weight:600;">Home</a>
            <span style="margin:0 8px;color:#ccc;">/</span>
            <span>Notice</span>
            <span style="margin:0 8px;color:#ccc;">/</span>
            <span style="color:var(--text);">{{ Str::limit($notice->title, 50) }}</span>
        </small>
    </div>
</div>

<section class="section-block">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                {{-- Notice badge --}}
                <div class="mb-4">
                    <span style="background:var(--green-pale);color:var(--green-dark);padding:6px 18px;border-radius:30px;font-size:13px;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas fa-bell"></i> Official Notice
                    </span>
                </div>
                <h2 style="font-family:'Montserrat',sans-serif;font-weight:800;color:var(--dark);margin-bottom:24px;font-size:clamp(1.4rem,3vw,2rem);">{{ $notice->title }}</h2>

                <div class="detail-body">
                    {!! $notice->description !!}
                </div>

                <div class="mt-5 pt-4" style="border-top:1px solid var(--border);display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="{{ route('home') }}" class="btn-gplc-outline"><i class="fas fa-arrow-left me-2"></i> Back to Home</a>
                    <a href="{{ route('contact') }}" class="btn-gplc"><i class="fas fa-question-circle me-2"></i> Have Questions?</a>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="100">
                {{-- Contact card --}}
                <div style="background:linear-gradient(135deg,var(--dark),var(--green-dark));border-radius:12px;padding:28px;color:#fff;margin-bottom:20px;">
                    <h5 style="font-family:'Montserrat',sans-serif;font-weight:800;margin-bottom:14px;">Need Help?</h5>
                    <p style="font-size:13.5px;color:rgba(255,255,255,.75);margin-bottom:20px;line-height:1.7;">For any queries regarding this notice, please contact our admin office.</p>
                    <a href="tel:025586701" style="display:flex;align-items:center;gap:10px;color:var(--green-light);font-weight:700;font-size:14px;margin-bottom:10px;text-decoration:none;">
                        <i class="fas fa-phone-alt"></i> 025-586701
                    </a>
                    <a href="mailto:info@gplc.edu.np" style="display:flex;align-items:center;gap:10px;color:var(--green-light);font-weight:700;font-size:14px;text-decoration:none;">
                        <i class="fas fa-envelope"></i> info@gplc.edu.np
                    </a>
                </div>

                {{-- Other notices --}}
                @php $otherNotices = App\Models\Notice::where('id','!=',$notice->id)->latest()->take(4)->get(); @endphp
                @if($otherNotices->count() > 0)
                    <div style="background:#fff;border-radius:12px;box-shadow:var(--shadow-sm);overflow:hidden;">
                        <div style="background:var(--green-pale);padding:14px 20px;border-bottom:1px solid var(--border);">
                            <h6 style="font-family:'Montserrat',sans-serif;font-weight:800;margin:0;color:var(--dark);font-size:14px;">Other Notices</h6>
                        </div>
                        @foreach($otherNotices as $on)
                            <a href="{{ url('notice/detail/' . $on->id) }}" style="display:block;padding:14px 20px;border-bottom:1px solid #f5f5f5;font-size:13.5px;font-weight:600;color:var(--text);text-decoration:none;transition:var(--transition);" onmouseover="this.style.background='var(--green-pale)';this.style.color='var(--green-dark)'" onmouseout="this.style.background='';this.style.color='var(--text)'">
                                <i class="fas fa-bell me-2" style="color:var(--green-dark);font-size:11px;"></i>{{ Str::limit($on->title, 50) }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
