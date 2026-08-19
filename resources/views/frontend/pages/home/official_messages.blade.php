@if($messages->count() > 0)
    <section class="section-block official-message-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-tag">Leadership</span>
                <h2 class="section-title mt-2">Messages from Officials</h2>
                <div class="section-divider center"></div>
            </div>
            <div class="row g-4">
                @foreach($messages as $msg)
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                        <div class="msg-card official-msg-card">
                            <div class="msg-card-header official-msg-header">
                                @if($msg->image)
                                    <img src="{{ asset($msg->image) }}" alt="{{ $msg->name }}" class="person-photo" loading="lazy">
                                @else
                                    <div class="no-photo">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                                <h5>{{ $msg->name }}</h5>
                                <span class="desg-badge">{{ $msg->designation }}</span>
                            </div>
                            <div class="msg-card-body official-msg-body">
                                <div class="open-quote">&ldquo;</div>
                                <p>{{ \Illuminate\Support\Str::limit($msg->message, 240) }}</p>
                                <button type="button" class="official-msg-btn" data-bs-toggle="modal" data-bs-target="#officialMessageModal{{ $msg->id }}">
                                    View More
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @foreach($messages as $msg)
        <div class="modal fade official-msg-modal" id="officialMessageModal{{ $msg->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0 pb-0">
                        <div class="d-flex align-items-center gap-3">
                            @if($msg->image)
                                <img src="{{ asset($msg->image) }}" alt="{{ $msg->name }}" class="official-msg-modal-photo" loading="lazy">
                            @else
                                <div class="official-msg-modal-placeholder">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="modal-title fw-bold mb-1">{{ $msg->name }}</h5>
                                <span class="official-msg-modal-badge">{{ $msg->designation }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-3">
                        <div class="official-msg-modal-quote">&ldquo;</div>
                        <div class="official-msg-modal-copy">
                            {!! nl2br(e($msg->message)) !!}
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
