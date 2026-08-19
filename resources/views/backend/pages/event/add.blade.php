@extends('backend.pages.layout.master')
@push('b-title', 'Add Event')
@section('backend-content')
<div class="row">
    <div class="mb-3">
        <a href="{{ route('event.table') }}" class="btn btn-success">&nbsp;&nbsp;Table</a>
    </div>
    <h5 class="h4" style="text-align: center; margin:10px 0;">Add Event</h5>
</div>
<br>
<form action="{{ route('event.store') }}" enctype="multipart/form-data" method="POST" style="width: 100%;">
    @csrf
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="mb-3">
                <label for="" class="form-label">Event Name</label>
                <input type="text" name="name" value="{{ old('name') }}" id="" class="form-control" placeholder=""
                    aria-describedby="helpId">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Calendar Category</label>
                <select name="event_type" class="form-control" id="eventTypeSelector">
                    <option value="event" {{ old('event_type') === 'event' ? 'selected' : '' }}>General Event</option>
                    <option value="holiday" {{ old('event_type') === 'holiday' ? 'selected' : '' }}>Holiday</option>
                    <option value="exam" {{ old('event_type') === 'exam' ? 'selected' : '' }}>Exam</option>
                    <option value="test" {{ old('event_type') === 'test' ? 'selected' : '' }}>Test</option>
                    <option value="cca_eca" {{ old('event_type') === 'cca_eca' ? 'selected' : '' }}>CCA / ECA</option>
                    <option value="result" {{ old('event_type') === 'result' ? 'selected' : '' }}>Result</option>
                </select>
            </div>
            <div class="alert alert-light border" id="eventCategoryHint" style="font-size: 13px;">
                Choose a category to show the relevant fields for this item.
            </div>
            <div class="mb-3">
                <label for="" class="form-label" id="eventDateLabel">Event Visit Date</label>
                <input type="date" name="visit_date" value="{{ old('visit_date') }}" id="" class="form-control" placeholder=""
                    aria-describedby="helpId">
            </div>
            <div class="mb-3" data-event-field="venue">
                <label for="" class="form-label">Venue / Notes</label>
                <input type="text" name="venue" value="{{ old('venue', 'GPLC Campus, Itahari') }}" class="form-control">
            </div>
            <div class="mb-3" data-event-field="result_link">
                <label for="" class="form-label">Result Link (optional)</label>
                <input type="text" name="result_link" value="{{ old('result_link') }}" class="form-control" placeholder="Paste result page URL if this is a result notice">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Upload a Image</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Event Gallery Images</label>
                <input type="file" name="gallery[]" multiple class="form-control" accept="image/*">
                <small class="text-muted">Upload multiple supporting images for the public event gallery.</small>
            </div>
        </div>
        <div class="col-md-12">
            <label for="">Full Description About Your Event..</label>
            <textarea id="summernote" name="description">
                {{ old('description') }}
            </textarea>
        </div>
    </div>
    <div class="mb-3" style="margin: 10px 0;">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
@push('scripts')
<script>
    (() => {
        $('#summernote').summernote({
          placeholder: 'Write more about your course',
          tabsize: 2,
          height: 420,
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['codeview', 'help']]
          ]
        });

        const eventTypeSelector = document.getElementById('eventTypeSelector');
        const eventDateLabel = document.getElementById('eventDateLabel');
        const eventCategoryHint = document.getElementById('eventCategoryHint');
        const venueField = document.querySelector('[data-event-field="venue"]');
        const resultField = document.querySelector('[data-event-field="result_link"]');

        const config = {
            event: { date: 'Event Date', showVenue: true, showResult: false, hint: 'Use this for seminars, programs, celebrations, and other regular events.' },
            holiday: { date: 'Holiday Date', showVenue: false, showResult: false, hint: 'Use this for holidays and breaks. Venue is not required here.' },
            exam: { date: 'Exam Date', showVenue: true, showResult: false, hint: 'Use this for exams. You can mention the hall, room, or exam notes in venue.' },
            test: { date: 'Test Date', showVenue: true, showResult: false, hint: 'Use this for class tests, internal tests, or assessment dates.' },
            cca_eca: { date: 'Activity Date', showVenue: true, showResult: false, hint: 'Use this for CCA/ECA activities, competitions, and student participation events.' },
            result: { date: 'Result Publish Date', showVenue: false, showResult: true, hint: 'Use this for result publication. Add the result link so students can open it quickly.' },
        };

        const syncEventFields = () => {
            const selected = eventTypeSelector.value || 'event';
            const current = config[selected] || config.event;
            eventDateLabel.textContent = current.date;
            eventCategoryHint.textContent = current.hint;
            venueField.style.display = current.showVenue ? '' : 'none';
            resultField.style.display = current.showResult ? '' : 'none';
        };

        eventTypeSelector?.addEventListener('change', syncEventFields);
        syncEventFields();
    })();
</script>
@endpush
@endsection
