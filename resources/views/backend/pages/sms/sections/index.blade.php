@extends('backend.pages.layout.master')
@push('b-title', 'Sections')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Sections</h3>
            <p class="text-muted mb-0">View and manage section names. To add sections to a class, go to the <a href="{{ route('sms.academic-classes.index') }}">Classes</a> page.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg"></i> Add Section
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Section Name</th>
                            <th>Capacity</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sections as $section)
                            <tr>
                                <td>{{ $section->name }}</td>
                                <td><span class="badge bg-secondary">{{ $section->capacity ?? 'Unlimited' }}</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $section->id }}">Edit</button>
                                    <form action="{{ route('sms.sections.destroy', $section->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach($sections as $section)
        <div class="modal fade" id="editModal{{ $section->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('sms.sections.update', $section->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- Pass class_id silently so validation doesn't fail --}}
                        <input type="hidden" name="academic_class_id" value="{{ $section->academic_class_id }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Section</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold text-uppercase">Class</label>
                                <div class="fw-semibold">{{ $section->academicClass->name ?? 'N/A' }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Section Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $section->name }}" required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Capacity (Optional)</label>
                                <input type="number" name="capacity" class="form-control" value="{{ $section->capacity }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('sms.sections.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info border-0 py-2 small mb-3">
                            <i class="bi bi-info-circle me-1"></i> To assign this section to a class, go to the
                            <a href="{{ route('sms.academic-classes.index') }}" class="fw-semibold">Classes page</a>.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Section Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. A  or  A, B, C  or  Neptune, Pluto" required>
                            <div class="form-text">Separate multiple names with commas to create them all at once.</div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Capacity (Optional)</label>
                            <input type="number" name="capacity" class="form-control" placeholder="e.g. 40">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
