@extends('backend.pages.layout.master')

@section('title', 'Manage Exams')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-0">Exams</h3>
            <p class="text-muted">Manage academic exams and terms.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('sms.exams.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Create Exam
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Title</th>
                            <th>Academic Year</th>
                            <th>Date Range</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $exam->title }}</td>
                                <td>{{ $exam->academicYear->name ?? '-' }}</td>
                                <td>
                                    {{ $exam->start_date ? $exam->start_date->format('M d, Y') : '-' }} 
                                    to 
                                    {{ $exam->end_date ? $exam->end_date->format('M d, Y') : '-' }}
                                </td>
                                <td>
                                    @if($exam->status == 'Upcoming')
                                        <span class="badge bg-info text-dark">Upcoming</span>
                                    @elseif($exam->status == 'Ongoing')
                                        <span class="badge bg-warning text-dark">Ongoing</span>
                                    @else
                                        <span class="badge bg-success">Completed</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('sms.exams.show', $exam->id) }}" class="btn btn-sm btn-outline-info" title="Manage Exam">
                                            <i class="bi bi-gear"></i> Manage
                                        </a>
                                        <a href="{{ route('sms.exams.edit', $exam->id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('sms.exams.destroy', $exam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this exam? All schedules and marks will be permanently deleted!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No exams found. <a href="{{ route('sms.exams.create') }}">Create one</a>.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($exams->hasPages())
            <div class="card-footer bg-white border-top">
                {{ $exams->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
