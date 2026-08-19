@if($counter)
    @php
        $counterItems = [
            ['title' => $counter->title1, 'value' => $counter->counter1, 'suffix' => $counter->suffix1, 'icon' => $counter->icon1],
            ['title' => $counter->title2, 'value' => $counter->counter2, 'suffix' => $counter->suffix2, 'icon' => $counter->icon2],
            ['title' => $counter->title3, 'value' => $counter->counter3, 'suffix' => $counter->suffix3, 'icon' => $counter->icon3],
            ['title' => $counter->title4, 'value' => $counter->counter4, 'suffix' => $counter->suffix4, 'icon' => $counter->icon4],
        ];
    @endphp
    <section class="gplc-stats sectionWorkdata">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-tag">{{ $counter->section_tag }}</span>
                <h2 class="section-title mt-2">{{ $counter->section_title }}</h2>
                <div class="section-divider center"></div>
                @if($counter->section_description)
                    <p class="mt-3 text-white-50 mx-auto" style="max-width: 680px;">{{ $counter->section_description }}</p>
                @endif
            </div>
            <div class="row align-items-stretch justify-content-center g-3">
                @foreach($counterItems as $item)
                    <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="stat-box h-100">
                            <div class="stat-icon">
                                <i class="{{ $item['icon'] }}"></i>
                            </div>
                            <div class="d-flex align-items-start justify-content-center">
                                <span class="counter-number" data-number="{{ (int) $item['value'] }}">0</span>
                                <span class="counter-suffix">{{ $item['suffix'] }}</span>
                            </div>
                            <p>{{ $item['title'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
