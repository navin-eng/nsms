@extends('backend.pages.layout.master')
@section('title', 'Issue / Return Book')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Issue Book</h5>
        <p class="text-muted small mb-0">Scan or enter book barcode to issue to students or staff.</p>
    </div>
    <a href="{{ route('sms.library.issues.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-list me-1"></i> View Issue History
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('sms.library.issues.store') }}" method="POST" id="issueBookForm">
                    @csrf
                    <div class="row g-4">
                        
                        <!-- 1. Select Borrower -->
                        <div class="col-12">
                            <h6 class="fw-bold mb-3"><span class="badge bg-primary me-2">1</span> Select Borrower</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <select name="borrower_type" id="borrower_type" class="form-select" required>
                                        <option value="App\Models\Student">Student</option>
                                        <option value="App\Models\Staff">Staff / Teacher</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" id="borrower_id_search" class="form-control" placeholder="Enter ID or type Name to search..." list="borrower_list" autocomplete="off" required>
                                        <button type="button" class="btn btn-primary" id="btnVerifyBorrower">Verify</button>
                                    </div>
                                    <datalist id="borrower_list"></datalist>
                                    <input type="hidden" name="borrower_id" id="borrower_id_hidden">
                                </div>
                            </div>
                            
                            <!-- Borrower Details Card -->
                            <div class="card bg-light border-0 mt-3 d-none" id="borrowerDetailsCard">
                                <div class="card-body py-2 px-3 d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-check fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold" id="borrowerNameDisplay">Name</h6>
                                        <span class="small text-muted" id="borrowerInfoDisplay">Class/Dept</span>
                                    </div>
                                    <div class="ms-auto text-success fw-medium">
                                        <i class="bi bi-check-circle-fill me-1"></i> Verified
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Scan Book -->
                        <div class="col-12">
                            <hr class="text-muted opacity-25">
                            <h6 class="fw-bold mb-3"><span class="badge bg-primary me-2">2</span> Scan Book Copy</h6>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-upc-scan"></i></span>
                                        <input type="text" name="barcode" id="barcode_input" class="form-control" placeholder="Enter or scan book barcode (e.g. LIB-2026-0001-001)" required>
                                        <button type="button" class="btn btn-primary" id="btnVerifyBook">Verify</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Book Details Card -->
                            <div class="card bg-light border-0 mt-3 d-none" id="bookDetailsCard">
                                <div class="card-body py-2 px-3 d-flex align-items-center gap-3">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-book fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold" id="bookTitleDisplay">Book Title</h6>
                                        <span class="small text-muted" id="bookInfoDisplay">Author - ISBN</span>
                                    </div>
                                    <div class="ms-auto text-success fw-medium">
                                        <i class="bi bi-check-circle-fill me-1"></i> Available
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Confirm Issue -->
                        <div class="col-12">
                            <hr class="text-muted opacity-25">
                            <h6 class="fw-bold mb-3"><span class="badge bg-primary me-2">3</span> Issue Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Issue Date</label>
                                    <input type="date" class="form-control bg-light" value="{{ date('Y-m-d') }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                                    <input type="date" name="due_date" id="due_date_input" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold" id="btnSubmitIssue" disabled>
                            <i class="bi bi-check2-circle me-1"></i> Confirm Issue Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm border-top border-primary border-3 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle text-primary me-2"></i>Library Rules</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted">Max Borrow Days (Student)</span>
                        <span class="fw-bold">{{ $settings->max_borrow_days_student }} Days</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted">Max Borrow Days (Staff)</span>
                        <span class="fw-bold">{{ $settings->max_borrow_days_staff }} Days</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted">Fine Per Day (Late)</span>
                        <span class="fw-bold text-danger">${{ number_format($settings->fine_per_day, 2) }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted">Max Books (Student)</span>
                        <span class="fw-bold">{{ $settings->max_books_student }} Books</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let borrowerVerified = false;
    let bookVerified = false;
    
    const settings = {
        studentDays: {{ $settings->max_borrow_days_student }},
        staffDays: {{ $settings->max_borrow_days_staff }}
    };

    function checkSubmitState() {
        if (borrowerVerified && bookVerified) {
            $('#btnSubmitIssue').prop('disabled', false);
        } else {
            $('#btnSubmitIssue').prop('disabled', true);
        }
    }
    
    function setDueDate() {
        const type = $('#borrower_type').val();
        const days = type === 'App\\Models\\Student' ? settings.studentDays : settings.staffDays;
        
        let date = new Date();
        date.setDate(date.getDate() + days);
        
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        let today = date.getFullYear() + "-" + (month) + "-" + (day);
        
        $('#due_date_input').val(today);
    }
    
    function loadBorrowersList() {
        const type = $('#borrower_type').val();
        $.ajax({
            url: "{{ route('sms.library.issues.api.borrowers-list') }}",
            type: "GET",
            data: { type: type },
            success: function(response) {
                if (response.success) {
                    let datalist = $('#borrower_list');
                    datalist.empty();
                    response.borrowers.forEach(function(borrower) {
                        datalist.append('<option value="' + borrower.id + '">' + borrower.text + '</option>');
                    });
                }
            }
        });
    }

    $('#borrower_type').on('change', function() {
        setDueDate();
        loadBorrowersList();
    });
    
    setDueDate(); // Initial call
    loadBorrowersList(); // Initial call
    
    // Auto-verify when selecting from datalist
    $('#borrower_id_search').on('input', function() {
        var val = $(this).val();
        var opts = $('#borrower_list').find('option');
        for(var i=0; i<opts.length; i++) {
            if(opts[i].value === val) {
                $('#btnVerifyBorrower').click();
                break;
            }
        }
    });

    // Verify Borrower
    $('#btnVerifyBorrower').on('click', function() {
        const type = $('#borrower_type').val();
        const id = $('#borrower_id_search').val();
        
        if (!id) return alert('Please enter ID');
        
        $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
        
        $.ajax({
            url: "{{ route('sms.library.issues.api.borrower') }}",
            type: "GET",
            data: { type: type, id: id },
            success: function(response) {
                $('#btnVerifyBorrower').html('Verify').prop('disabled', false);
                if (response.success) {
                    $('#borrowerNameDisplay').text(response.name + ' (' + response.identifier + ')');
                    $('#borrowerInfoDisplay').text(response.info);
                    $('#borrowerDetailsCard').removeClass('d-none');
                    $('#borrower_id_hidden').val(id);
                    borrowerVerified = true;
                } else {
                    alert(response.message);
                    $('#borrowerDetailsCard').addClass('d-none');
                    $('#borrower_id_hidden').val('');
                    borrowerVerified = false;
                }
                checkSubmitState();
            },
            error: function() {
                $('#btnVerifyBorrower').html('Verify').prop('disabled', false);
                alert('An error occurred');
            }
        });
    });

    // Verify Book
    $('#btnVerifyBook').on('click', function() {
        const barcode = $('#barcode_input').val();
        
        if (!barcode) return alert('Please enter barcode');
        
        $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
        
        $.ajax({
            url: "{{ route('sms.library.issues.api.book') }}",
            type: "GET",
            data: { barcode: barcode },
            success: function(response) {
                $('#btnVerifyBook').html('Verify').prop('disabled', false);
                if (response.success) {
                    $('#bookTitleDisplay').text(response.title);
                    $('#bookInfoDisplay').text('By ' + (response.author || 'Unknown') + ' | ISBN: ' + (response.isbn || 'N/A'));
                    $('#bookDetailsCard').removeClass('d-none');
                    bookVerified = true;
                } else {
                    alert(response.message);
                    $('#bookDetailsCard').addClass('d-none');
                    bookVerified = false;
                }
                checkSubmitState();
            },
            error: function() {
                $('#btnVerifyBook').html('Verify').prop('disabled', false);
                alert('An error occurred');
            }
        });
    });
});
</script>
@endpush
@endsection
