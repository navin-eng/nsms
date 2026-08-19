@extends('frontend.layout.master')

@section('frontend-content')
<div class="container py-5" style="min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="fw-bold" style="color: var(--primary-color);">Student Results</h1>
                <p class="text-muted">Enter your Symbol Number and select your exam to view your result.</p>
            </div>

            <!-- Search Form -->
            <div class="card shadow-sm border-0 mb-5" style="border-radius: 12px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                <div class="card-body p-4 p-md-5">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('results.search') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Select Examination</label>
                                <select name="exam_id" class="form-select" required>
                                    <option value="">-- Choose Exam --</option>
                                    @foreach($exams as $ex)
                                        <option value="{{ $ex->id }}" {{ (isset($exam) && $exam->id == $ex->id) ? 'selected' : '' }}>
                                            {{ $ex->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Symbol Number</label>
                                <input type="text" name="symbol_number" class="form-control" placeholder="e.g. 1001" value="{{ request('symbol_number') }}" required>
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn text-white px-5 py-2 fw-bold" style="background-color: var(--primary-color); border-radius: 8px;">
                                    View Result
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Result Card -->
            @if(isset($result))
                <div class="card shadow border-0" id="resultCard" style="border-radius: 12px; border-top: 5px solid var(--primary-color) !important;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4 border-bottom pb-3">
                            <h2 class="fw-bold h3 mb-1" style="color: var(--primary-color);">{{ \App\Models\SiteSetting::current()->site_name }}</h2>
                            <h4 class="text-muted h5 mb-3">{{ $exam->title }}</h4>
                            <p class="mb-0 text-dark"><strong>Student Name:</strong> {{ $result->student_name }}</p>
                            <p class="mb-0 text-dark"><strong>Symbol Number:</strong> {{ $result->symbol_number }}</p>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead style="background-color: var(--primary-light); color: white;">
                                    <tr>
                                        <th style="background-color: var(--primary-light); color: white;">Subject / Field</th>
                                        <th style="background-color: var(--primary-light); color: white;" class="text-center">Marks / Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($result->marks_data as $key => $val)
                                        <tr>
                                            <td class="fw-semibold text-secondary">{{ $key }}</td>
                                            <td class="text-center fw-bold">{{ $val }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-4 d-print-none">
                            <button onclick="window.print()" class="btn btn-outline-secondary">
                                <i class="fas fa-print"></i> Print Result
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #resultCard, #resultCard * {
            visibility: visible;
        }
        #resultCard {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
@endsection
