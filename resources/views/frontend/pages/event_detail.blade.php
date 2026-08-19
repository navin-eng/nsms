@extends('frontend.layout.master')
@section('frontend-content')

{{-- Hero --}}
<div class="detail-hero">
    <img src="{{ asset($event->image) }}" alt="{{ $event->name }}">
    <div class="overlay"></div>
    <div class="dh-title">
        <h1>{{ $event->name }}</h1>
    </div>
</div>

<section class="section-block">
    <div class="container">
        <div class="row g-5">
            {{-- Main Content --}}
            <div class="col-lg-8" data-aos="fade-up">
                <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
                    <span style="background:var(--green-pale);color:var(--green-dark);padding:6px 18px;border-radius:30px;font-size:13px;font-weight:700;">
                        <i class="fas fa-calendar-alt me-1"></i> {{ $event->visit_date }}
                    </span>
                    <span style="background:#f8f9fa;color:#555;padding:6px 18px;border-radius:30px;font-size:13px;font-weight:600;">
                        <i class="fas fa-tag me-1"></i> {{ $event->event_type_label ?? 'Campus Event' }}
                    </span>
                </div>
                <div class="detail-body">
                    {!! $event->description !!}
                </div>

                @if(!empty($event->gallery))
                    <div class="mt-5">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <h4 style="font-family:'Montserrat',sans-serif;font-weight:800;color:var(--dark);margin:0;">Event Gallery</h4>
                            <span style="font-size:13px;color:var(--text-muted);">Photos from this event</span>
                        </div>
                        <div id="eventGalleryRow" class="event-gallery-grid">
                            @foreach(($event->gallery ?? []) as $img)
                                <a href="{{ asset($img) }}" data-lg-size="1400-900">
                                    <img src="{{ asset($img) }}" alt="{{ $event->name }}">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="mt-5 pt-4" style="border-top:1px solid var(--border);">
                    <a href="{{ route('home') }}" class="btn-gplc-outline"><i class="fas fa-arrow-left me-2"></i> Back to Home</a>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div style="background:#fff;border-radius:12px;box-shadow:var(--shadow-md);overflow:hidden;position:sticky;top:100px;">
                    <div style="background:linear-gradient(135deg,var(--dark),var(--green-dark));padding:20px 24px;color:#fff;">
                        <h5 style="font-family:'Montserrat',sans-serif;font-weight:800;margin:0;">Event Details</h5>
                    </div>
                    <div style="padding:24px;">
                        <table style="width:100%;font-size:14px;border-collapse:collapse;">
                            <tr>
                                <td style="padding:10px 0;color:#888;font-weight:600;border-bottom:1px solid #f0f0f0;white-space:nowrap;">
                                    <i class="fas fa-calendar-alt me-2" style="color:var(--green-dark);"></i>Date
                                </td>
                                <td style="padding:10px 0;font-weight:700;border-bottom:1px solid #f0f0f0;">{{ $event->visit_date }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;color:#888;font-weight:600;">
                                    <i class="fas fa-map-marker-alt me-2" style="color:var(--green-dark);"></i>Venue
                                </td>
                                <td style="padding:10px 0;font-weight:700;">{{ $event->venue ?: 'GPLC Campus, Itahari' }}</td>
                            </tr>
                        </table>
                        @if($event->result_link)
                            <a href="{{ $event->result_link }}" target="_blank" class="btn-gplc-outline d-flex justify-content-center mt-3">
                                <i class="fas fa-file-circle-check me-2"></i> View Result
                            </a>
                        @endif
                        <a href="{{ route('contact') }}" class="btn-gplc d-flex justify-content-center mt-4">
                            <i class="fas fa-paper-plane me-2"></i> Enquire Now
                        </a>
                    </div>
                </div>

                @php $otherEvents = App\Models\Event::where('status',1)->where('id','!=',$event->id)->latest()->take(3)->get(); @endphp
                @if($otherEvents->count() > 0)
                    <div class="mt-4">
                        <h6 style="font-family:'Montserrat',sans-serif;font-weight:800;margin-bottom:16px;color:var(--dark);font-size:15px;">More Events</h6>
                        @foreach($otherEvents as $oe)
                            <a href="{{ url('event/' . $oe->slug) }}" style="display:flex;gap:12px;margin-bottom:12px;padding:12px;border-radius:10px;background:#fff;box-shadow:var(--shadow-sm);transition:var(--transition);" class="text-decoration-none" onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform='none'">
                                <img src="{{ asset($oe->image) }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;flex-shrink:0;" alt="">
                                <div>
                                    <div style="font-size:13px;font-weight:700;color:var(--dark);line-height:1.4;">{{ Str::limit($oe->name,40) }}</div>
                                    <div style="font-size:11px;color:var(--green-dark);margin-top:3px;font-weight:600;"><i class="fas fa-calendar-alt me-1"></i>{{ $oe->visit_date }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .event-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
    }

    .event-gallery-grid a {
        display: block;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .event-gallery-grid img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        transition: transform .25s ease;
    }

    .event-gallery-grid a:hover img {
        transform: scale(1.05);
    }
</style>
@endpush

@push('scripts')
<script>
    (() => {
        const eventGallery = document.getElementById('eventGalleryRow');
        if (eventGallery && typeof lightGallery !== 'undefined') {
            lightGallery(eventGallery, { speed: 500, plugins: [lgZoom], download: false });
        }
    })();
</script>
@endpush
