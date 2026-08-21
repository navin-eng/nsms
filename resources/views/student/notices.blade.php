@extends('student.layout.master')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">School Notices</h4>
        <p class="text-muted">Important announcements and updates from the school.</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($notices as $notice)
                <a href="{{ route('student.notices.show', $notice->id) }}" class="list-group-item list-group-item-action p-4 border-bottom">
                    <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                        <h5 class="mb-0 fw-bold text-dark">{{ $notice->title }}</h5>
                        <small class="badge bg-light text-muted border px-3 py-2 rounded-pill"><i class="bi bi-clock me-1"></i>{{ $notice->created_at->format('M d, Y') }}</small>
                    </div>
                    <p class="mb-0 text-muted">{{ Str::limit(strip_tags($notice->content), 150) }}</p>
                    <div class="mt-3 text-primary small fw-semibold">Read more <i class="bi bi-arrow-right"></i></div>
                </a>
            @empty
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-bell-slash fs-1 d-block mb-3 opacity-50"></i>
                    No notices available at the moment.
                </div>
            @endforelse
        </div>
    </div>
    @if($notices->hasPages())
    <div class="card-footer bg-white border-top py-3">
        {{ $notices->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
