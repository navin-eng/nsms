@extends('backend.pages.layout.master')
@push('b-title', 'Exam Results')

@section('backend-content')
@include('sweetalert::alert')

<div class="admin-page-header">
    <h1 class="aph-title">Exam Results System</h1>
    <p class="aph-sub">Manage exams and import student results via CSV.</p>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="card-title">Create New Exam</span>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('exam.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Exam Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. 1st Semester Final">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="0">Draft (Hidden)</option>
                            <option value="1">Published (Public)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-admin btn-admin-primary w-100">Create Exam</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="card-title">Exams List</span>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Exam Title</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exams as $exam)
                                <tr>
                                    <td><strong>{{ $exam->title }}</strong></td>
                                    <td>
                                        @if($exam->status == 1)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $exam->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('exam.show', $exam->id) }}" class="btn-admin btn-admin-sm btn-admin-primary">Manage Results</a>
                                        
                                        <button class="btn-admin btn-admin-sm btn-admin-light ms-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $exam->id }}">Edit</button>
                                        
                                        <a href="{{ route('exam.destroy', $exam->id) }}" class="btn-admin btn-admin-sm btn-admin-light text-danger ms-1" onclick="return confirm('Delete this exam and ALL results?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $exam->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Exam</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('exam.update', $exam->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Exam Title</label>
                                                        <input type="text" name="title" class="form-control" value="{{ $exam->title }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select name="status" class="form-control">
                                                            <option value="0" {{ $exam->status == 0 ? 'selected' : '' }}>Draft (Hidden)</option>
                                                            <option value="1" {{ $exam->status == 1 ? 'selected' : '' }}>Published (Public)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn-admin btn-admin-primary">Update Exam</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No exams created yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
