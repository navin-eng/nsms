@extends('backend.pages.layout.master')
@push('b-title', 'Academic Classes')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Classes</h3>
            <p class="text-muted mb-0">Manage all classes and assign them to optional streams.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg"></i> Add Class
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Class Name</th>
                            <th>Numeric Value</th>
                            <th>Stream (Optional)</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $class)
                            <tr>
                                <td>{{ $class->name }}</td>
                                <td><span class="badge bg-secondary">{{ $class->numeric_value }}</span></td>
                                <td>{{ $class->stream ? $class->stream->name : 'General / None' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $class->id }}">Edit</button>
                                    <form action="{{ route('sms.academic-classes.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
    @foreach($classes as $class)
        <div class="modal fade" id="editModal{{ $class->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('sms.academic-classes.update', $class->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Class</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $class->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Numeric Value (for sorting)</label>
                                <input type="number" name="numeric_value" class="form-control" value="{{ $class->numeric_value }}" required min="1">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Stream (Optional)</label>
                                <select name="stream_id" class="form-select">
                                    <option value="">-- No Stream --</option>
                                    @foreach($streams as $stream)
                                        <option value="{{ $stream->id }}" {{ $class->stream_id == $stream->id ? 'selected' : '' }}>{{ $stream->name }}</option>
                                    @endforeach
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
                <form action="{{ route('sms.academic-classes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Class</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name (e.g. Class 10)</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Numeric Value (for sorting, e.g. 10)</label>
                            <input type="number" name="numeric_value" class="form-control" required min="1">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Stream (Optional)</label>
                            <select name="stream_id" class="form-select">
                                <option value="">-- No Stream --</option>
                                @foreach($streams as $stream)
                                    <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
