@extends('backend.pages.layout.master')
@push('b-title', 'Manage Results: ' . $exam->title)

@section('backend-content')
@include('sweetalert::alert')

<div class="admin-page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="aph-title">{{ $exam->title }} - Results</h1>
        <p class="aph-sub">Import or manually add student results for this exam.</p>
    </div>
    <a href="{{ route('exam.index') }}" class="btn-admin btn-admin-light">Back to Exams</a>
</div>

<div class="row g-4 mb-4">
    <!-- CSV Import -->
    <div class="col-lg-6">
        <div class="admin-card h-100">
            <div class="admin-card-header d-flex justify-content-between align-items-center">
                <span class="card-title">Bulk Import (CSV)</span>
                <a href="{{ route('exam.sample') }}" class="btn btn-sm btn-outline-success"><i class="bi bi-download"></i> Download Sample CSV</a>
            </div>
            <div class="admin-card-body">
                <div class="alert alert-info">
                    <strong>Instructions:</strong> Your CSV file must have headers in the first row. It <b>must</b> include <code>Symbol Number</code> and <code>Student Name</code>. All other columns (subjects, GPA, etc.) will be imported automatically as marks data.
                </div>
                <form action="{{ route('exam.import', $exam->id) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                    @csrf
                    <input type="file" name="csv_file" class="form-control me-2" accept=".csv" required>
                    <button type="submit" class="btn-admin btn-admin-primary">Import</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Manual Add -->
    <div class="col-lg-6">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <span class="card-title">Manually Add Result</span>
            </div>
            <div class="admin-card-body">
                <button type="button" class="btn-admin btn-admin-primary w-100" data-bs-toggle="modal" data-bs-target="#addManualModal">
                    <i class="bi bi-plus-circle"></i> Add Single Result
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Results Table -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <span class="card-title">Imported Results ({{ $exam->results->count() }})</span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Symbol Number</th>
                        <th>Student Name</th>
                        <th>Marks / Data</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exam->results as $res)
                        <tr>
                            <td><strong>{{ $res->symbol_number }}</strong></td>
                            <td>{{ $res->student_name }}</td>
                            <td>
                                @foreach($res->marks_data as $key => $val)
                                    <span class="badge bg-light text-dark border me-1">{{ $key }}: {{ $val }}</span>
                                @endforeach
                            </td>
                            <td class="text-end">
                                <button class="btn-admin btn-admin-sm btn-admin-light" data-bs-toggle="modal" data-bs-target="#editResModal{{ $res->id }}">Edit</button>
                                <a href="{{ route('exam.result.destroy', $res->id) }}" class="btn-admin btn-admin-sm btn-admin-light text-danger ms-1" onclick="return confirm('Delete this result?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>

                        <!-- Edit Result Modal -->
                        <div class="modal fade" id="editResModal{{ $res->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Result: {{ $res->student_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('exam.result.update', $res->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Symbol Number</label>
                                                    <input type="text" name="symbol_number" class="form-control" value="{{ $res->symbol_number }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Student Name</label>
                                                    <input type="text" name="student_name" class="form-control" value="{{ $res->student_name }}" required>
                                                </div>
                                            </div>
                                            
                                            <h6>Marks Data (Dynamic)</h6>
                                            <div id="dynamicFieldsEdit{{ $res->id }}">
                                                @foreach($res->marks_data as $key => $val)
                                                    <div class="row g-2 mb-2 dynamic-row">
                                                        <div class="col-5">
                                                            <input type="text" name="marks_keys[]" class="form-control" value="{{ $key }}" placeholder="Subject/Key">
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" name="marks_values[]" class="form-control" value="{{ $val }}" placeholder="Marks/Value">
                                                        </div>
                                                        <div class="col-2">
                                                            <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.dynamic-row').remove()"><i class="bi bi-x"></i></button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addField('dynamicFieldsEdit{{ $res->id }}')">+ Add Field</button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn-admin btn-admin-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No results found for this exam.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Manual Result Modal -->
<div class="modal fade" id="addManualModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('exam.result.store', $exam->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Symbol Number</label>
                            <input type="text" name="symbol_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Student Name</label>
                            <input type="text" name="student_name" class="form-control" required>
                        </div>
                    </div>
                    
                    <h6>Marks Data (Dynamic)</h6>
                    <div id="dynamicFieldsAdd">
                        <div class="row g-2 mb-2 dynamic-row">
                            <div class="col-5">
                                <input type="text" name="marks_keys[]" class="form-control" placeholder="Subject/Key (e.g. Math)">
                            </div>
                            <div class="col-5">
                                <input type="text" name="marks_values[]" class="form-control" placeholder="Marks/Value (e.g. 95)">
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.dynamic-row').remove()"><i class="bi bi-x"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addField('dynamicFieldsAdd')">+ Add Field</button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-admin btn-admin-primary">Save Result</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function addField(containerId) {
        const container = document.getElementById(containerId);
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 dynamic-row';
        row.innerHTML = `
            <div class="col-5">
                <input type="text" name="marks_keys[]" class="form-control" placeholder="Subject/Key">
            </div>
            <div class="col-5">
                <input type="text" name="marks_values[]" class="form-control" placeholder="Marks/Value">
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.dynamic-row').remove()"><i class="bi bi-x"></i></button>
            </div>
        `;
        container.appendChild(row);
    }
</script>
@endpush
