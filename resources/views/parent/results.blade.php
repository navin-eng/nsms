@extends('parent.layout.master')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">Academic Results</h4>
        <p class="text-muted">View published exam results and annual transcripts for {{ $child->first_name }}.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Published Term Exams</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Exam Title</th>
                                <th>Academic Year</th>
                                <th>Date Published</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exams as $exam)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $exam->title }}</td>
                                    <td>{{ $exam->academicYear->name ?? 'N/A' }}</td>
                                    <td>{{ $exam->updated_at->format('M d, Y') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('parent.results.print', $exam->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-printer me-1"></i> View Report Card
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-file-earmark-x fs-1 d-block mb-3 text-black-50"></i>
                                        No published exams found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Annual Transcripts</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($years as $year)
                        <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $year->name }}</h6>
                                <span class="small text-muted">Consolidated Result</span>
                            </div>
                            <a href="{{ route('parent.results.transcript', $year->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="bi bi-download"></i>
                            </a>
                        </li>
                    @empty
                        <li class="list-group-item p-4 text-center text-muted">
                            No transcripts available.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
