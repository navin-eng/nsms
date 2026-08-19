@if($events->count() > 0)
    <section style="background: var(--green-bg); padding: 70px 0;">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-5" data-aos="fade-up">
                <div>
                    <span class="section-tag">Campus Life</span>
                    <h2 class="section-title mt-2 mb-0">Events &amp; Calendar Highlights</h2>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('events.index') }}" class="btn-gplc">
                        <i class="fa-solid fa-calendar-week"></i> View Events
                    </a>
                    <a href="{{ route('calendar') }}" class="btn-gplc-light">
                        <i class="fa-solid fa-calendar-days"></i> View Calendar
                    </a>
                </div>
            </div>
            <div class="event-stack-grid">
                @foreach($events->take(6) as $event)
                    <a href="{{ url('event/' . $event->slug) }}" class="event-stack-tile" data-aos="fade-up" data-aos-delay="{{ $loop->index * 70 }}">
                        <div class="event-stack-media">
                            <img src="{{ asset($event->image) }}" alt="{{ $event->name }}" loading="lazy">
                            <span class="event-stack-type">{{ $event->event_type_label }}</span>
                        </div>
                        <div class="event-stack-body">
                            <div class="event-stack-date">
                                <i class="fa-regular fa-calendar"></i> {{ $event->visit_date }}
                            </div>
                            <h5>{{ $event->name }}</h5>
                            <p>{{ Str::words(strip_tags($event->description), 18) }}</p>
                            <span class="event-stack-link">Open Event <i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
