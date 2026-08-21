@extends('backend.pages.layout.master')
@section('title', 'Student ID Cards')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0 fw-bold">Student Identity Cards Management</h5>
            <p class="text-muted small mb-0">Efficiently generate and print identity cards for your students.</p>
        </div>
    </div>

    {{-- 1. Class & Section Selection --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3"><span class="badge bg-primary me-2">1</span> Class & Section Filter</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Class <span class="text-danger">*</span></label>
                    <select name="class_id" id="classFilter" class="form-select" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Section (Optional)</label>
                    <select name="section_id" id="sectionFilter" class="form-select">
                        <option value="">All Sections</option>
                        @foreach($sections as $s)
                            <option value="{{ $s->id }}" {{ $selectedSectionId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" id="btnLoadStudents" class="btn btn-primary w-100"><i class="bi bi-people me-1"></i> Load Students</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Student Selection (Hidden by default, populated via AJAX) --}}
    <div class="card border-0 shadow-sm mb-4 d-none" id="studentSelectionCard">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3"><span class="badge bg-primary me-2">2</span> Select Students</h6>
            <form action="{{ route('sms.id-cards.students') }}" method="GET" id="generateForm">
                <input type="hidden" name="class_id" id="hidden_class_id" value="{{ $selectedClassId }}">
                <input type="hidden" name="section_id" id="hidden_section_id" value="{{ $selectedSectionId }}">
                
                <div class="table-responsive border rounded" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover table-sm mb-0 align-middle">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="50" class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="selectAllStudents" checked>
                                    </div>
                                </th>
                                <th>Student Name</th>
                                <th>Admission No</th>
                                <th>Roll No</th>
                            </tr>
                        </thead>
                        <tbody id="studentListBody">
                            <!-- AJAX content -->
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-success fw-semibold px-4"><i class="bi bi-magic me-1"></i> Generate Preview</button>
                </div>
            </form>
        </div>
    </div>

    @if($students->isNotEmpty() && request()->has('student_ids'))
        {{-- 3. Print Settings & Live Preview --}}
        <div class="card border-0 shadow-sm mb-4 border-top border-primary border-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><span class="badge bg-primary me-2">3</span> Print Settings & Preview ({{ $students->count() }} Cards)</h6>
            </div>
            <div class="card-body bg-light border-bottom">
                <form action="{{ route('sms.id-cards.students') }}" method="GET" class="row g-3 align-items-end" id="printSettingsForm">
                    <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                    <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
                    @foreach(request()->input('student_ids', []) as $stId)
                        <input type="hidden" name="student_ids[]" value="{{ $stId }}">
                    @endforeach

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Card Layout</label>
                        <select name="layout" class="form-select form-select-sm">
                            <option value="portrait" {{ $layout == 'portrait' ? 'selected' : '' }}>Portrait (Vertical)</option>
                            <option value="landscape" {{ $layout == 'landscape' ? 'selected' : '' }}>Landscape (Horizontal)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Template Theme</label>
                        <select name="template_id" class="form-select form-select-sm">
                            <option value="modern" {{ $selectedTemplateId == 'modern' ? 'selected' : '' }}>Modern Blue</option>
                            <option value="classic" {{ $selectedTemplateId == 'classic' ? 'selected' : '' }}>Classic Navy</option>
                            <option value="elegant" {{ $selectedTemplateId == 'elegant' ? 'selected' : '' }}>Elegant Maroon</option>
                            @if(isset($customTemplates) && $customTemplates->count() > 0)
                                <optgroup label="Custom Templates">
                                    @foreach($customTemplates as $ct)
                                        <option value="{{ $ct->id }}" {{ $selectedTemplateId == $ct->id ? 'selected' : '' }}>{{ $ct->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Print Format</label>
                        <select name="print_format" class="form-select form-select-sm">
                            <option value="a4" {{ $printFormat == 'a4' ? 'selected' : '' }}>A4 Sheet (Grid)</option>
                            <option value="id_printer" {{ $printFormat == 'id_printer' ? 'selected' : '' }}>ID Card Printer Machine (CR80)</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-outline-secondary w-50"><i class="bi bi-arrow-clockwise me-1"></i> Update Preview</button>
                        <button type="button" class="btn btn-sm btn-primary w-50" onclick="printCards()"><i class="bi bi-printer me-1"></i> Print Now</button>
                    </div>
                </form>
            </div>
            <div class="card-body p-4 bg-light">
                <div class="row g-4 justify-content-center">
                    @foreach($students as $st)
                        @php
                            $verifyUrl = route('verification.show', ['token' => 'id_student_' . $st->id . '_' . substr(md5($st->id . config('app.key')), 0, 8)]);
                            $enrollment = $st->currentEnrollment;
                        @endphp
                        <div class="col-auto">
                            @php
                                $customTemplate = isset($customTemplates) ? $customTemplates->firstWhere('id', (int) $selectedTemplateId) : null;
                            @endphp

                            @if($customTemplate)
                                @php
                                    $html = $customTemplate->html_content;
                                    $html = str_replace('[FULL_NAME]', $st->full_name, $html);
                                    $html = str_replace('[ID_NO]', $st->admission_no ?? $st->id, $html);
                                    $html = str_replace('[CLASS_NAME]', $enrollment?->academicClass?->name ?? '', $html);
                                    $html = str_replace('[SECTION_NAME]', $enrollment?->section?->name ?? '', $html);
                                    $html = str_replace('[ROLL_NO]', $enrollment?->roll_number ?? '', $html);
                                    $html = str_replace('[BLOOD_GROUP]', $st->blood_group ?? '', $html);
                                    $html = str_replace('[PHONE]', $st->phone ?? $st->guardian?->guardian_phone ?? '', $html);
                                    $html = str_replace('[DOB]', $st->date_of_birth ? \Carbon\Carbon::parse($st->date_of_birth)->format('d-m-Y') : '', $html);
                                    $photoUrl = $st->photo ? asset('uploads/students/' . $st->photo) : asset('assets/images/user-placeholder.png');
                                    $html = str_replace('[PHOTO_URL]', $photoUrl, $html);
                                    $html = str_replace('[SCHOOL_NAME]', $setting->title ?? 'BLESSED SACRAMENT', $html);
                                    $html = str_replace('[SCHOOL_ADDRESS]', $setting->address ?? 'Lalitpur, Nepal', $html);
                                    $logoUrl = ($setting && $setting->logo) ? asset('uploads/site_settings/' . $setting->logo) : '';
                                    $html = str_replace('[SCHOOL_LOGO]', $logoUrl, $html);
                                @endphp
                                <style>
                                    {!! $customTemplate->css_content !!}
                                </style>
                                <div class="shadow-sm rounded-3 overflow-hidden bg-white border"
                                    style="width: {{ $customTemplate->layout === 'portrait' ? '215px; height: 330px;' : '325px; height: 205px;' }}">
                                    {!! $html !!}
                                </div>
                            @else
                                @if($layout === 'portrait')
                                    {{-- Portrait Card Preview (CR80 approx 54mm x 85.6mm) --}}
                                    <div class="id-card-portrait shadow-sm rounded-3 overflow-hidden bg-white border">
                                        <div class="id-header text-center p-2 text-white"
                                            style="background: linear-gradient(135deg, #1e3a8a, #3b82f6);">
                                            @if($setting && $setting->logo)
                                                <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" alt="Logo"
                                                    style="height: 24px; filter: brightness(0) invert(1);">
                                            @endif
                                            <div class="fw-bold text-uppercase" style="font-size: 8.5pt; letter-spacing: 0.5px;">
                                                {{ $setting->title ?? 'BLESSED SACRAMENT' }}</div>
                                            <div style="font-size: 6pt; opacity: 0.9;">STUDENT IDENTITY CARD</div>
                                        </div>
                                        <div class="id-body text-center p-3">
                                            <div class="avatar-box mb-2 mx-auto"
                                                style="width: 70px; height: 75px; border-radius: 8px; border: 2px solid #3b82f6; overflow: hidden; background:#e5e7eb;">
                                                @if($st->photo)
                                                    <img src="{{ asset('uploads/students/' . $st->photo) }}"
                                                        style="width:100%; height:100%; object-fit:cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted fw-bold"
                                                        style="font-size: 16pt;">
                                                        {{ strtoupper(substr($st->first_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 10pt;">{{ $st->full_name }}</div>
                                            <div class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0 mb-2"
                                                style="font-size: 7pt;">
                                                {{ $enrollment?->academicClass?->name ?? 'Class' }} -
                                                {{ $enrollment?->section?->name ?? 'Sec' }}
                                            </div>

                                            <table class="table table-sm table-borderless text-start mb-2"
                                                style="font-size: 7pt; line-height: 1.3;">
                                                <tr>
                                                    <td class="text-muted p-0" style="width:45%;">Adm No:</td>
                                                    <td class="fw-bold text-dark p-0">{{ $st->admission_no ?? '#' . $st->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted p-0">Roll No:</td>
                                                    <td class="fw-bold text-dark p-0">{{ $enrollment?->roll_number ?? '—' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted p-0">Blood Group:</td>
                                                    <td class="fw-bold text-danger p-0">{{ $st->blood_group ?? '—' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted p-0">Emergency:</td>
                                                    <td class="fw-bold text-dark p-0">
                                                        {{ $st->phone ?? $st->guardian?->guardian_phone ?? '—' }}</td>
                                                </tr>
                                            </table>

                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                                <div class="text-start">
                                                    {!! \App\Services\QrCodeService::barcodeSvg($st->admission_no ?? (string) $st->id, 95, 24) !!}
                                                </div>
                                                <div>
                                                    {!! \App\Services\QrCodeService::svg($verifyUrl, 36) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Landscape Card Preview (CR80 approx 85.6mm x 54mm) --}}
                                    <div
                                        class="id-card-landscape shadow-sm rounded-3 overflow-hidden bg-white border d-flex flex-column justify-content-between">
                                        <div class="id-header p-2 text-white d-flex justify-content-between align-items-center"
                                            style="background: linear-gradient(135deg, #1e3a8a, #3b82f6);">
                                            <div class="d-flex align-items-center gap-2">
                                                @if($setting && $setting->logo)
                                                    <img src="{{ asset('uploads/site_settings/' . $setting->logo) }}" alt="Logo"
                                                        style="height: 20px; filter: brightness(0) invert(1);">
                                                @endif
                                                <div class="fw-bold text-uppercase" style="font-size: 8pt;">
                                                    {{ $setting->title ?? 'BLESSED SACRAMENT' }}</div>
                                            </div>
                                            <span class="badge bg-white text-primary" style="font-size: 6pt;">STUDENT</span>
                                        </div>
                                        <div class="id-body p-2 d-flex gap-3 align-items-center">
                                            <div class="avatar-box"
                                                style="width: 65px; height: 75px; border-radius: 6px; border: 2px solid #3b82f6; overflow: hidden; background:#e5e7eb; flex-shrink: 0;">
                                                @if($st->photo)
                                                    <img src="{{ asset('uploads/students/' . $st->photo) }}"
                                                        style="width:100%; height:100%; object-fit:cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted fw-bold"
                                                        style="font-size: 14pt;">
                                                        {{ strtoupper(substr($st->first_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1" style="font-size: 7pt; line-height: 1.3;">
                                                <div class="fw-bold text-dark text-truncate" style="font-size: 9.5pt;">{{ $st->full_name }}
                                                </div>
                                                <div class="text-primary fw-semibold mb-1">
                                                    {{ $enrollment?->academicClass?->name ?? 'Class' }}
                                                    ({{ $enrollment?->section?->name ?? 'Sec' }})</div>
                                                <div><span class="text-muted">Adm No:</span>
                                                    <strong>{{ $st->admission_no ?? '#' . $st->id }}</strong></div>
                                                <div><span class="text-muted">Blood:</span> <strong
                                                        class="text-danger">{{ $st->blood_group ?? '—' }}</strong></div>
                                                <div><span class="text-muted">Emergency:</span>
                                                    <strong>{{ $st->phone ?? $st->guardian?->guardian_phone ?? '—' }}</strong></div>
                                            </div>
                                            <div class="flex-shrink-0 text-center">
                                                {!! \App\Services\QrCodeService::svg($verifyUrl, 42) !!}
                                                <div style="font-size: 5pt; color: #6b7280;">VERIFY</div>
                                            </div>
                                        </div>
                                        <div class="id-footer px-2 py-1 bg-light border-top d-flex justify-content-between align-items-center"
                                            style="font-size: 6pt;">
                                            <span class="text-muted">{{ $setting->address ?? 'Lalitpur, Nepal' }}</span>
                                            <span class="fw-bold">Principal Signature</span>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-person-vcard fs-1 d-block mb-3 opacity-50"></i>
                <h6>Select Class to Generate Student ID Cards</h6>
                <p class="small mb-0">Choose an academic class above to preview and batch print student ID cards.</p>
            </div>
        </div>
    @endif

    <style>
        .id-card-portrait {
            width: 215px;
            height: 330px;
        }

        .id-card-landscape {
            width: 325px;
            height: 205px;
        }
    </style>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#classFilter').on('change', function () {
                    var classId = $(this).val();
                    var sectionSelect = $('#sectionFilter');

                    sectionSelect.html('<option value="">Loading...</option>');

                    if (classId) {
                        $.ajax({
                            url: "{{ route('admin.communications.sections-by-class') }}",
                            type: "GET",
                            data: { class_ids: [classId] },
                            success: function (data) {
                                sectionSelect.html('<option value="">All Sections</option>');
                                $.each(data, function (key, section) {
                                    sectionSelect.append('<option value="' + section.id + '">' + section.name + '</option>');
                                });
                            },
                            error: function () {
                                sectionSelect.html('<option value="">Error loading sections</option>');
                            }
                        });
                    } else {
                        sectionSelect.html('<option value="">All Sections</option>');
                    }
                });
                
                // 2. Load Students via AJAX
                $('#btnLoadStudents').on('click', function() {
                    var classId = $('#classFilter').val();
                    var sectionId = $('#sectionFilter').val();
                    
                    if (!classId) {
                        alert('Please select a class first.');
                        return;
                    }
                    
                    $(this).html('<i class="spinner-border spinner-border-sm me-1"></i> Loading...');
                    $(this).prop('disabled', true);
                    
                    $.ajax({
                        url: "{{ route('sms.id-cards.api.students') }}",
                        type: "GET",
                        data: { class_id: classId, section_id: sectionId },
                        success: function(students) {
                            $('#btnLoadStudents').html('<i class="bi bi-people me-1"></i> Load Students').prop('disabled', false);
                            
                            var tbody = $('#studentListBody');
                            tbody.empty();
                            
                            if (students.length === 0) {
                                tbody.append('<tr><td colspan="4" class="text-center py-4 text-muted">No students found in this class.</td></tr>');
                            } else {
                                $.each(students, function(index, student) {
                                    var roll = student.roll_number ? student.roll_number : '-';
                                    var adm = student.admission_no ? student.admission_no : '#'+student.id;
                                    var row = '<tr>' +
                                        '<td class="text-center"><div class="form-check d-flex justify-content-center"><input class="form-check-input student-checkbox" type="checkbox" name="student_ids[]" value="' + student.id + '" checked></div></td>' +
                                        '<td class="fw-medium">' + student.name + '</td>' +
                                        '<td>' + adm + '</td>' +
                                        '<td>' + roll + '</td>' +
                                        '</tr>';
                                    tbody.append(row);
                                });
                            }
                            
                            // Update hidden inputs for generate form
                            $('#hidden_class_id').val(classId);
                            $('#hidden_section_id').val(sectionId);
                            
                            // Show checklist card
                            $('#studentSelectionCard').removeClass('d-none');
                        },
                        error: function() {
                            $('#btnLoadStudents').html('<i class="bi bi-people me-1"></i> Load Students').prop('disabled', false);
                            alert('An error occurred while loading students.');
                        }
                    });
                });
                
                // Select All Checkbox
                $('#selectAllStudents').on('change', function() {
                    $('.student-checkbox').prop('checked', $(this).prop('checked'));
                });
            });

            // Print Function
            function printCards() {
                var form = document.getElementById('printSettingsForm');
                // Create a hidden input for print=1
                var printInput = document.createElement('input');
                printInput.type = 'hidden';
                printInput.name = 'print';
                printInput.value = '1';
                form.appendChild(printInput);
                
                // Set target to _blank
                var originalTarget = form.target;
                form.target = '_blank';
                
                // Submit
                form.submit();
                
                // Cleanup
                form.removeChild(printInput);
                form.target = originalTarget;
            }
        </script>
    @endpush
@endsection