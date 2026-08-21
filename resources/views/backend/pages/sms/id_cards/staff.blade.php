@extends('backend.pages.layout.master')
@section('title', 'Staff ID Cards')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0 fw-bold">Student Identity Cards Management</h5>
            <p class="text-muted small mb-0">Efficiently generate and print identity cards for your staff.</p>
        </div>
    </div>

    {{-- 1. Class & Section Selection --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3"><span class="badge bg-primary me-2">1</span> Department Filter</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Class <span class="text-danger">*</span></label>
                    <select name="department_id" id="classFilter" class="form-select" required>
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $c)
                            <option value="{{ $c->id }}" {{ $selectedDepartmentId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" id="btnLoadStudents" class="btn btn-primary w-100"><i class="bi bi-people me-1"></i> Load Staff</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Student Selection (Hidden by default, populated via AJAX) --}}
    <div class="card border-0 shadow-sm mb-4 d-none" id="staffSelectionCard">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3"><span class="badge bg-primary me-2">2</span> Select Staff</h6>
            <form action="{{ route('sms.id-cards.staff') }}" method="GET" id="generateForm">
                <input type="hidden" name="department_id" id="hidden_department_id" value="{{ $selectedDepartmentId }}">
                
                
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
                        <tbody id="staffListBody">
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

    @if($staffMembers->isNotEmpty() && request()->has('staff_ids'))
        {{-- 3. Print Settings & Live Preview --}}
        <div class="card border-0 shadow-sm mb-4 border-top border-primary border-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><span class="badge bg-primary me-2">3</span> Print Settings & Preview ({{ $staffMembers->count() }} Cards)</h6>
            </div>
            <div class="card-body bg-light border-bottom">
                <form action="{{ route('sms.id-cards.staff') }}" method="GET" class="row g-3 align-items-end" id="printSettingsForm">
                    <input type="hidden" name="department_id" value="{{ $selectedDepartmentId }}">
                    
                    @foreach(request()->input('staff_ids', []) as $staffId)
                        <input type="hidden" name="staff_ids[]" value="{{ $staffId }}">
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
                    @foreach($staffMembers as $staff)
                        @php
                            $verifyUrl = route('verification.show', ['token' => 'id_staff_' . $staff->id . '_' . substr(md5($staff->id . config('app.key')), 0, 8)]);
                            $enrollment = $staff->currentEnrollment;
                        @endphp
                        <div class="col-auto">
                            @php
                                $customTemplate = isset($customTemplates) ? $customTemplates->firstWhere('id', (int) $selectedTemplateId) : null;
                            @endphp

                            @if($customTemplate)
                                @php
                                    $html = $customTemplate->html_content;
                                    $html = str_replace('[FULL_NAME]', $staff->full_name, $html);
                                    $html = str_replace('[ID_NO]', $staff->employee_id ?? $staff->id, $html);
                                    $html = str_replace('[CLASS_NAME]', $staff->department?->name ?? '', $html);
                                    $html = str_replace('[SECTION_NAME]', '', $html);
                                    $html = str_replace('[ROLL_NO]', $staff->designation?->name ?? '', $html);
                                    $html = str_replace('[BLOOD_GROUP]', $staff->blood_group ?? '', $html);
                                    $html = str_replace('[PHONE]', $staff->phone ?? $staff->emergency_contact ?? '', $html);
                                    $html = str_replace('[DOB]', $staff->dob ? \Carbon\Carbon::parse($staff->dob)->format('d-m-Y') : '', $html);
                                    $photoUrl = $staff->photo ? asset('uploads/staff/' . $staff->photo) : asset('assets/images/user-placeholder.png');
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
                                            <div style="font-size: 6pt; opacity: 0.9;">STAFF IDENTITY CARD</div>
                                        </div>
                                        <div class="id-body text-center p-3">
                                            <div class="avatar-box mb-2 mx-auto"
                                                style="width: 70px; height: 75px; border-radius: 8px; border: 2px solid #3b82f6; overflow: hidden; background:#e5e7eb;">
                                                @if($staff->photo)
                                                    <img src="{{ asset('uploads/staff/' . $staff->photo) }}"
                                                        style="width:100%; height:100%; object-fit:cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted fw-bold"
                                                        style="font-size: 16pt;">
                                                        {{ strtoupper(substr($staff->first_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 10pt;">{{ $staff->full_name }}</div>
                                            <div class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0 mb-2"
                                                style="font-size: 7pt;">
                                                {{ $staff->department?->name ?? 'Dept' }}
                                                
                                            </div>

                                            <table class="table table-sm table-borderless text-start mb-2"
                                                style="font-size: 7pt; line-height: 1.3;">
                                                <tr>
                                                    <td class="text-muted p-0" style="width:45%;">Staff ID:</td>
                                                    <td class="fw-bold text-dark p-0">{{ $staff->employee_id ?? '#' . $staff->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted p-0">Designation:</td>
                                                    <td class="fw-bold text-dark p-0">{{ $staff->designation?->name ?? '—' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted p-0">Blood Group:</td>
                                                    <td class="fw-bold text-danger p-0">{{ $staff->blood_group ?? '—' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted p-0">Emergency:</td>
                                                    <td class="fw-bold text-dark p-0">
                                                        {{ $staff->phone ?? $staff->emergency_contact ?? '—' }}</td>
                                                </tr>
                                            </table>

                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                                <div class="text-start">
                                                    {!! \App\Services\QrCodeService::barcodeSvg($staff->employee_id ?? (string) $staff->id, 95, 24) !!}
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
                                            <span class="badge bg-white text-primary" style="font-size: 6pt;">STAFF</span>
                                        </div>
                                        <div class="id-body p-2 d-flex gap-3 align-items-center">
                                            <div class="avatar-box"
                                                style="width: 65px; height: 75px; border-radius: 6px; border: 2px solid #3b82f6; overflow: hidden; background:#e5e7eb; flex-shrink: 0;">
                                                @if($staff->photo)
                                                    <img src="{{ asset('uploads/staff/' . $staff->photo) }}"
                                                        style="width:100%; height:100%; object-fit:cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted fw-bold"
                                                        style="font-size: 14pt;">
                                                        {{ strtoupper(substr($staff->first_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1" style="font-size: 7pt; line-height: 1.3;">
                                                <div class="fw-bold text-dark text-truncate" style="font-size: 9.5pt;">{{ $staff->full_name }}
                                                </div>
                                                <div class="text-primary fw-semibold mb-1">
                                                    {{ $staff->department?->name ?? 'Class' }}
                                                    ()</div>
                                                <div><span class="text-muted">Staff ID:</span>
                                                    <strong>{{ $staff->employee_id ?? '#' . $staff->id }}</strong></div>
                                                <div><span class="text-muted">Blood Grp:</span> <strong
                                                        class="text-danger">{{ $staff->blood_group ?? '—' }}</strong></div>
                                                <div><span class="text-muted">Emergency:</span>
                                                    <strong>{{ $staff->phone ?? $staff->emergency_contact ?? '—' }}</strong></div>
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
                <h6>Select Department to Generate Staff ID Cards</h6>
                <p class="small mb-0">Choose an academic class above to preview and batch print staff ID cards.</p>
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
                // 2. Load Staff via AJAX
                $('#btnLoadStudents').on('click', function() {
                    var departmentId = $('#classFilter').val();
                    
                    
                    if (!departmentId) {
                        alert('Please select a department first.');
                        return;
                    }
                    
                    $(this).html('<i class="spinner-border spinner-border-sm me-1"></i> Loading...');
                    $(this).prop('disabled', true);
                    
                    $.ajax({
                        url: "{{ route('sms.id-cards.api.staff') }}",
                        type: "GET",
                        data: { department_id: departmentId },
                        success: function(staffMembersList) {
                            $('#btnLoadStudents').html('<i class="bi bi-people me-1"></i> Load Staff').prop('disabled', false);
                            
                            var tbody = $('#staffListBody');
                            tbody.empty();
                            
                            if (staffMembersList.length === 0) {
                                tbody.append('<tr><td colspan="4" class="text-center py-4 text-muted">No staff found in this department.</td></tr>');
                            } else {
                                $.each(staffMembersList, function(index, staffMember) {
                                    var roll = staffMember.designation ? staffMember.designation : '-';
                                    var adm = staffMember.staff_id ? staffMember.staff_id : '#'+staffMember.id;
                                    var row = '<tr>' +
                                        '<td class="text-center"><div class="form-check d-flex justify-content-center"><input class="form-check-input staff-checkbox" type="checkbox" name="staff_ids[]" value="' + staffMember.id + '" checked></div></td>' +
                                        '<td class="fw-medium">' + staffMember.name + '</td>' +
                                        '<td>' + adm + '</td>' +
                                        '<td>' + roll + '</td>' +
                                        '</tr>';
                                    tbody.append(row);
                                });
                            }
                            
                            // Update hidden inputs for generate form
                            $('#hidden_department_id').val(departmentId);
                            
                            
                            // Show checklist card
                            $('#staffSelectionCard').removeClass('d-none');
                        },
                        error: function() {
                            $('#btnLoadStudents').html('<i class="bi bi-people me-1"></i> Load Staff').prop('disabled', false);
                            alert('An error occurred while loading staff.');
                        }
                    });
                });
                
                // Select All Checkbox
                $('#selectAllStudents').on('change', function() {
                    $('.staff-checkbox').prop('checked', $(this).prop('checked'));
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