<section class="section-block">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">What We Offer</span>
            <h2 class="section-title mt-2">Our Academic Programs</h2>
            <div class="section-divider center"></div>
        </div>
        <div class="row">
            @forelse($courses as $course)
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <div class="course-card">
                        <div class="card-img">
                            <img src="{{ asset($course->image) }}" alt="{{ $course->name }}" loading="lazy">
                            <span class="card-badge">Degree Program</span>
                        </div>
                        <div class="card-body">
                            <h5>{{ $course->name }}</h5>
                            <p>{{ Str::limit($course->description, 100) }}</p>
                            <a href="{{ url('course/' . $course->slug) }}" class="btn-gplc">
                                <i class="fa-solid fa-arrow-right"></i> Learn More
                            </a>
                        </div>
                        <div class="card-footer-gplc">
                            <span class="duration">
                                <i class="fa-regular fa-clock"></i>
                                {{ $course->duration }} Years
                            </span>
                            <a href="{{ url('course/' . $course->slug) }}">View Details &rarr;</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">No programs available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
