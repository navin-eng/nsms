@php
    $entry = $entry ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $entry->title ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Type</label>
        <select name="entry_type" class="form-control" required>
            <option value="holiday" {{ old('entry_type', $entry->entry_type ?? '') === 'holiday' ? 'selected' : '' }}>Holiday</option>
            <option value="exam" {{ old('entry_type', $entry->entry_type ?? '') === 'exam' ? 'selected' : '' }}>Exam</option>
            <option value="test" {{ old('entry_type', $entry->entry_type ?? '') === 'test' ? 'selected' : '' }}>Test</option>
            <option value="cca_eca" {{ old('entry_type', $entry->entry_type ?? '') === 'cca_eca' ? 'selected' : '' }}>CCA / ECA</option>
            <option value="result" {{ old('entry_type', $entry->entry_type ?? '') === 'result' ? 'selected' : '' }}>Result</option>
            <option value="other" {{ old('entry_type', $entry->entry_type ?? 'other') === 'other' ? 'selected' : '' }}>Other</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $entry->start_date ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $entry->end_date ?? '') }}">
        <small class="text-muted">Use this for long holidays or multi-day schedules.</small>
    </div>
    <div class="col-12">
        <label class="form-label">Result Link</label>
        <input type="text" name="result_link" class="form-control" value="{{ old('result_link', $entry->result_link ?? '') }}" placeholder="Optional URL for published results">
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" placeholder="Add details for students, teachers, or parents...">{{ old('description', $entry->description ?? '') }}</textarea>
    </div>
</div>
