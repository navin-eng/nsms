@extends('backend.pages.layout.master')
@section('title', 'Library Settings')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Library Settings</h5>
        <p class="text-muted small mb-0">Configure borrowing limits, rules, and fines.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Borrowing Rules Configuration</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('sms.library.settings.update') }}" method="POST">
                    @csrf
                    
                    <h6 class="text-muted small fw-bold mb-3">Student Limits</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Max Borrowing Days</label>
                            <div class="input-group">
                                <input type="number" name="max_borrow_days_student" class="form-control" value="{{ $settings->max_borrow_days_student }}" min="1" required>
                                <span class="input-group-text">Days</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Max Books at a time</label>
                            <div class="input-group">
                                <input type="number" name="max_books_student" class="form-control" value="{{ $settings->max_books_student }}" min="1" required>
                                <span class="input-group-text">Books</span>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-muted small fw-bold mb-3">Staff Limits</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Max Borrowing Days</label>
                            <div class="input-group">
                                <input type="number" name="max_borrow_days_staff" class="form-control" value="{{ $settings->max_borrow_days_staff }}" min="1" required>
                                <span class="input-group-text">Days</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Max Books at a time</label>
                            <div class="input-group">
                                <input type="number" name="max_books_staff" class="form-control" value="{{ $settings->max_books_staff }}" min="1" required>
                                <span class="input-group-text">Books</span>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-muted small fw-bold mb-3">Fines Configuration</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fine Per Day (Late)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="fine_per_day" class="form-control" value="{{ $settings->fine_per_day }}" min="0" required>
                            </div>
                            <div class="form-text small">Amount to charge per day after due date.</div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Save Settings</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm border-top border-info border-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>How it works</h6>
            </div>
            <div class="card-body text-muted small p-4">
                <p>The library settings control the automated behaviors of the library module:</p>
                <ul class="mb-0">
                    <li class="mb-2"><strong>Max Borrowing Days:</strong> When issuing a book, the due date is automatically set based on this value from the current date.</li>
                    <li class="mb-2"><strong>Max Books at a time:</strong> Prevents issuing more than this number of books to a single user. (Currently tracked visually in the issue screen).</li>
                    <li><strong>Fine Per Day:</strong> When returning an overdue book, the system calculates the fine as: <code>(Days Late) × (Fine Per Day)</code>. The librarian can still manually override this amount before confirming the return.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
