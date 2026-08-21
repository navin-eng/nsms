@extends('parent.layout.master')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">School Notices & Announcements</h4>
        <p class="text-muted">Stay up to date with the latest information from the school administration.</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="accordion accordion-flush" id="noticesAccordion">
            @forelse($notices as $notice)
                <div class="accordion-item border-bottom">
                    <h2 class="accordion-header" id="heading{{ $notice->id }}">
                        <button class="accordion-button collapsed py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $notice->id }}" aria-expanded="false" aria-controls="collapse{{ $notice->id }}">
                            <div class="d-flex flex-column w-100 me-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-primary">{{ $notice->title }}</span>
                                    <span class="badge bg-light text-dark">{{ $notice->published_at ? \Carbon\Carbon::parse($notice->published_at)->format('M d, Y') : $notice->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="text-muted small text-truncate" style="max-width: 80%;">
                                    {{ Str::limit(strip_tags($notice->content), 100) }}
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse{{ $notice->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $notice->id }}" data-bs-parent="#noticesAccordion">
                        <div class="accordion-body p-4 bg-light">
                            <div class="notice-content">
                                {!! $notice->content !!}
                            </div>
                            @if($notice->attachment)
                                <hr>
                                <a href="{{ asset('storage/' . $notice->attachment) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="bi bi-paperclip me-1"></i> View Attachment
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bell-slash fs-1 d-block mb-3 text-black-50"></i>
                    No notices available at the moment.
                </div>
            @endforelse
        </div>
    </div>
    @if($notices->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $notices->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
