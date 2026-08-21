@extends('backend.pages.layout.master')
@section('title', 'ID Card Templates')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">ID Card Templates</h5>
        <p class="text-muted small mb-0">Design and manage custom templates for student and staff ID cards.</p>
    </div>
    <a href="{{ route('sms.id-cards.templates.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Create New Template
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">S.N.</th>
                        <th>Template Name</th>
                        <th>Type</th>
                        <th>Layout</th>
                        <th>Default</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                    <tr>
                        <td class="ps-4">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $template->name }}</td>
                        <td>
                            <span class="badge {{ $template->type === 'student' ? 'bg-primary' : 'bg-info' }} bg-opacity-10 text-{{ $template->type === 'student' ? 'primary' : 'info' }} px-2 py-1 rounded-pill text-capitalize">
                                {{ $template->type }}
                            </span>
                        </td>
                        <td class="text-capitalize">{{ $template->layout }}</td>
                        <td>
                            @if($template->is_default)
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i> Default
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('sms.id-cards.templates.edit', $template->id) }}" class="btn btn-sm btn-light text-primary me-1" title="Edit Template">
                                <i class="bi bi-pencil"></i> Design
                            </a>
                            <form action="{{ route('sms.id-cards.templates.destroy', $template->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this template?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-palette d-block fs-1 mb-3"></i>
                            No custom templates found. Create one to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
