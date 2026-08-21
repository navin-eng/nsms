@extends('backend.pages.layout.master')
@section('title', 'School Notices')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">School Notices</h5>
        <p class="text-muted small mb-0">Manage announcements targeting students, parents, and staff.</p>
    </div>
    <div>
        <a href="{{ route('sms.school-notices.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Notice
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($notices->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4">Notice Title</th>
                        <th>Target Audience</th>
                        <th>Status</th>
                        <th>Published At</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notices as $notice)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $notice->title }}</div>
                            <div class="text-muted small text-truncate" style="max-width: 300px;">{{ Str::limit(strip_tags($notice->description), 60) }}</div>
                        </td>
                        <td>
                            @if($notice->target_roles)
                                <div class="mb-1">
                                    <span class="text-muted small">Roles:</span>
                                    @foreach($notice->target_roles as $role)
                                        <span class="badge bg-secondary py-0 px-1">{{ ucfirst($role) }}</span>
                                    @endforeach
                                </div>
                            @endif
                            @if($notice->target_classes)
                                <div class="mb-1">
                                    <span class="text-muted small">Classes:</span>
                                    @foreach($notice->target_classes as $classId)
                                        @php $cls = \App\Models\AcademicClass::find($classId); @endphp
                                        <span class="badge bg-info text-dark py-0 px-1">{{ $cls->name ?? $classId }}</span>
                                    @endforeach
                                </div>
                            @endif
                            @if($notice->target_sections)
                                <div>
                                    <span class="text-muted small">Sections:</span>
                                    @foreach($notice->target_sections as $secId)
                                        @php $sec = \App\Models\Section::find($secId); @endphp
                                        <span class="badge bg-light text-dark py-0 px-1 border">{{ $sec->name ?? $secId }}</span>
                                    @endforeach
                                </div>
                            @endif
                            @if(!$notice->target_roles && !$notice->target_classes && !$notice->target_sections)
                                <span class="badge bg-light text-muted py-0 px-1 border">Global Broadcast</span>
                            @endif
                        </td>
                        <td>
                            @if($notice->status == 'published')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Published</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Draft</span>
                            @endif
                        </td>
                        <td class="text-muted">
                            {{ $notice->published_at ? $notice->published_at->format('d M Y, h:i A') : 'Not Published' }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('sms.school-notices.edit', $notice->id) }}" class="btn btn-sm btn-light border py-0 px-2 text-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('sms.school-notices.destroy', $notice->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notice?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border py-0 px-2 text-danger ms-1">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $notices->links('pagination::bootstrap-5') }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash fs-1 d-block mb-3 opacity-50"></i>
            <h6>No School Notices Found</h6>
            <p class="small mb-0">Create internal notices targeted at students, parents, and teachers.</p>
            <a href="{{ route('sms.school-notices.create') }}" class="btn btn-sm btn-primary mt-3">Create Notice</a>
        </div>
        @endif
    </div>
</div>
@endsection
