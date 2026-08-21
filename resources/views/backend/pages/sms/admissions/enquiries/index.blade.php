@extends('backend.pages.layout.master')
@push('b-title', 'Admission Enquiries')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Admission Enquiries</h3>
        <p class="text-muted mb-0">Track and manage prospective students.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEnquiryModal">
        <i class="bi bi-plus-circle"></i> New Enquiry
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Class Intended</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enquiries as $enq)
                    <tr>
                        <td>{{ $enq->enquiry_date->format('d M, Y') }}</td>
                        <td class="fw-semibold">{{ $enq->name }}</td>
                        <td>
                            <div>{{ $enq->phone }}</div>
                            @if($enq->email)<div class="small text-muted">{{ $enq->email }}</div>@endif
                        </td>
                        <td>{{ $enq->academicClass->name ?? '-' }}</td>
                        <td>{{ $enq->source ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $enq->status == 'Open' ? 'bg-primary' : ($enq->status == 'Followed Up' ? 'bg-warning' : 'bg-secondary') }}">
                                {{ $enq->status }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editEnquiryModal{{ $enq->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('sms.admissions.enquiries.destroy', $enq->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete enquiry?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editEnquiryModal{{ $enq->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('sms.admissions.enquiries.update', $enq->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Enquiry</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Name *</label>
                                            <input type="text" name="name" class="form-control" value="{{ $enq->name }}" required>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Phone *</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $enq->phone }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $enq->email }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Class</label>
                                                <select name="academic_class_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" {{ $enq->academic_class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Source</label>
                                                <select name="source" class="form-select">
                                                    <option value="Walk-in" {{ $enq->source == 'Walk-in' ? 'selected' : '' }}>Walk-in</option>
                                                    <option value="Phone" {{ $enq->source == 'Phone' ? 'selected' : '' }}>Phone</option>
                                                    <option value="Website" {{ $enq->source == 'Website' ? 'selected' : '' }}>Website</option>
                                                    <option value="Referral" {{ $enq->source == 'Referral' ? 'selected' : '' }}>Referral</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Status *</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="Open" {{ $enq->status == 'Open' ? 'selected' : '' }}>Open</option>
                                                    <option value="Followed Up" {{ $enq->status == 'Followed Up' ? 'selected' : '' }}>Followed Up</option>
                                                    <option value="Closed" {{ $enq->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Date *</label>
                                                <input type="date" name="enquiry_date" class="form-control" value="{{ $enq->enquiry_date->format('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Notes</label>
                                            <textarea name="notes" class="form-control" rows="2">{{ $enq->notes }}</textarea>
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
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No enquiries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="newEnquiryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('sms.admissions.enquiries.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Enquiry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone *</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Class</label>
                            <select name="academic_class_id" class="form-select">
                                <option value="">-- Select --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Source</label>
                            <select name="source" class="form-select">
                                <option value="Walk-in">Walk-in</option>
                                <option value="Phone">Phone</option>
                                <option value="Website">Website</option>
                                <option value="Referral">Referral</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Date *</label>
                            <input type="date" name="enquiry_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Enquiry</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
