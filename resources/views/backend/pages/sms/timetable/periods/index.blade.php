<?php
/**
 * Periods Management View
 *
 * Displays a list of periods with CRUD actions.
 * Uses Bootstrap 5 modal for create / edit forms.
 * Allows setting custom time overrides per period (optional).
 */
?>
@extends('backend.pages.layout.master')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h3 class="mb-0">Periods</h3>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#periodModal" id="addPeriodBtn">
                <i class="bi bi-plus-lg"></i> Add Period
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Break?</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periods as $period)
                    <tr data-id="{{ $period->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $period->name }}</td>
                        <td>{{ $period->start_time }}</td>
                        <td>{{ $period->end_time }}</td>
                        <td>{!! $period->is_break ? '<i class="bi bi-check-lg text-success"></i>' : '' !!}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary edit-period" data-id="{{ $period->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('sms.periods.destroy', $period) }}" class="d-inline" onsubmit="return confirm('Delete this period?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-3">No periods defined.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Period Modal (Create / Edit) --}}
<div class="modal fade" id="periodModal" tabindex="-1" aria-labelledby="periodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="periodForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="periodModalLabel">Add Period</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_break" id="isBreakCheck">
                        <label class="form-check-label" for="isBreakCheck">Mark as Break (e.g., Lunch)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('periodModal'));
    const form = document.getElementById('periodForm');
    const modalTitle = document.getElementById('periodModalLabel');

    // Handle Add button
    document.getElementById('addPeriodBtn').addEventListener('click', () => {
        form.action = "{{ route('sms.periods.store') }}";
        form.method = 'POST';
        modalTitle.textContent = 'Add Period';
        form.reset();
        // Ensure hidden _method field not present for create
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
    });

    // Edit click
    document.querySelectorAll('.edit-period').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            fetch(`{{ url('admin/sms/periods') }}/${id}`) // GET shows JSON via route? We'll rely on simple endpoint returning JSON
                .then(r => r.json())
                .then(data => {
                    form.action = `{{ url('admin/sms/periods') }}/${id}`;
                    // Insert hidden _method for PUT
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    form.method = 'POST';
                    modalTitle.textContent = 'Edit Period';
                    form.name.value = data.name;
                    form.start_time.value = data.start_time.slice(0,5);
                    form.end_time.value = data.end_time.slice(0,5);
                    document.getElementById('isBreakCheck').checked = data.is_break;
                    modal.show();
                });
        });
    });
});
</script>
@endpush
@endsection
