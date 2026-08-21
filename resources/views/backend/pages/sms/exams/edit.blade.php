@extends('backend.pages.layout.master')

@section('title', 'Edit Exam')

@section('backend-content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h3 class="mb-0">Edit Exam</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('sms.exams.index') }}" class="btn btn-outline-secondary">Back to Exams</a>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm mx-auto" style="max-width: 800px;">
            <div class="card-body p-4">
                <form action="{{ route('sms.exams.update', $exam->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Exam Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $exam->title) }}"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Academic Year <span class="text-danger">*</span></label>
                            <select name="academic_year_id" class="form-select" required>
                                <option value="">Select Year</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ old('academic_year_id', $exam->academic_year_id) == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ old('start_date', $exam->start_date ? $exam->start_date->format('Y-m-d') : '') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ old('end_date', $exam->end_date ? $exam->end_date->format('Y-m-d') : '') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="Upcoming" {{ old('status', $exam->status) == 'Upcoming' ? 'selected' : '' }}>
                                    Upcoming</option>
                                <option value="Ongoing" {{ old('status', $exam->status) == 'Ongoing' ? 'selected' : '' }}>
                                    Ongoing</option>
                                <option value="Completed" {{ old('status', $exam->status) == 'Completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control"
                                rows="3">{{ old('description', $exam->description) }}</textarea>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-5">Update Exam</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection