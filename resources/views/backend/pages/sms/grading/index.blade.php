@extends('backend.pages.layout.master')

@section('title', 'Grading Rules')

@section('backend-content')
    <div class="container-fluid py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0">Grading Rules</h3>
                <p class="text-muted">Define grade letters, percentage ranges, and GPA points.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRuleModal">
                    <i class="bi bi-plus-lg"></i> Add Rule
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Grade</th>
                                <th>Min %</th>
                                <th>Max %</th>
                                <th>Grade Point (GPA)</th>
                                <th>Remarks</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rules as $rule)
                                <tr>
                                    <td class="ps-4 fw-bold fs-5 text-primary">{{ $rule->grade_name }}</td>
                                    <td>{{ number_format($rule->min_percent, 2) }}%</td>
                                    <td>{{ number_format($rule->max_percent, 2) }}%</td>
                                    <td>{{ number_format($rule->grade_point, 2) }}</td>
                                    <td>{{ $rule->remarks ?? '-' }}</td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#editRuleModal{{ $rule->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('sms.grading-rules.destroy', $rule->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Delete this rule?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editRuleModal{{ $rule->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('sms.grading-rules.update', $rule->id) }}" method="POST"
                                            class="modal-content">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Grading Rule</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Grade Name (e.g. A+) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="grade_name" class="form-control" required
                                                        value="{{ $rule->grade_name }}">
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 mb-3">
                                                        <label class="form-label fw-bold">Min Percentage <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" min="0" max="100" name="min_percent"
                                                            class="form-control" required value="{{ $rule->min_percent }}">
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label class="form-label fw-bold">Max Percentage <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" min="0" max="100" name="max_percent"
                                                            class="form-control" required value="{{ $rule->max_percent }}">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Grade Point (GPA) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" min="0" max="4" name="grade_point"
                                                        class="form-control" required value="{{ $rule->grade_point }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Remarks</label>
                                                    <input type="text" name="remarks" class="form-control"
                                                        value="{{ $rule->remarks }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Rule</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No grading rules found. <button
                                            class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addRuleModal">Add
                                            one</button>.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addRuleModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('sms.grading-rules.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Grading Rule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Grade Name (e.g. A+) <span class="text-danger">*</span></label>
                        <input type="text" name="grade_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Min Percentage <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="min_percent" class="form-control"
                                required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Max Percentage <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="max_percent" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Grade Point (GPA) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="4" name="grade_point" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Remarks</label>
                        <input type="text" name="remarks" class="form-control" placeholder="e.g. Outstanding">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Rule</button>
                </div>
            </form>
        </div>
    </div>
@endsection