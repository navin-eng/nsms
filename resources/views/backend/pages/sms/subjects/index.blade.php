@extends('backend.pages.layout.master')
@push('b-title', 'Subjects')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Subjects</h3>
            <p class="text-muted mb-0">Manage all subjects offered by the school.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg"></i> Add Subject
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Subject Name</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                            <tr>
                                <td>{{ $subject->name }}</td>
                                <td><span class="badge bg-secondary">{{ $subject->code }}</span></td>
                                <td>
                                    <span class="badge {{ $subject->type === 'both' ? 'bg-info' : ($subject->type === 'practical' ? 'bg-warning text-dark' : 'bg-primary') }}">
                                        {{ ucfirst($subject->type) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $subject->id }}">Edit</button>
                                    <form action="{{ route('sms.subjects.destroy', $subject->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
    @foreach($subjects as $subject)
        <div class="modal fade" id="editModal{{ $subject->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('sms.subjects.update', $subject->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Subject</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $subject->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Code</label>
                                <input type="text" name="code" class="form-control" value="{{ $subject->code }}" required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="theory" {{ $subject->type === 'theory' ? 'selected' : '' }}>Theory Only</option>
                                    <option value="practical" {{ $subject->type === 'practical' ? 'selected' : '' }}>Practical Only</option>
                                    <option value="both" {{ $subject->type === 'both' ? 'selected' : '' }}>Theory & Practical</option>
                                </select>
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
                <form action="{{ route('sms.subjects.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name (e.g. Mathematics)</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code (e.g. MAT101)</label>
                            <input type="text" name="code" class="form-control" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="theory" selected>Theory Only</option>
                                <option value="practical">Practical Only</option>
                                <option value="both">Theory & Practical</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
