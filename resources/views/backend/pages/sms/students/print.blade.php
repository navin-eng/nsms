<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - {{ $student->full_name }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }
            body {
                background: #fff;
                color: #000;
                font-size: 12pt;
            }
            .no-print {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .table-bordered th, .table-bordered td {
                border-color: #000 !important;
            }
            th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }
        }
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
        }
        .print-container {
            max-width: 210mm; /* A4 width */
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .school-header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .school-header h2 {
            margin: 0;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
        }
        .school-header p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .section-title {
            background-color: #2c3e50;
            color: #fff;
            padding: 5px 10px;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 15px;
            -webkit-print-color-adjust: exact;
        }
        .student-photo {
            width: 120px;
            height: 140px;
            object-fit: cover;
            border: 1px solid #ccc;
            padding: 3px;
        }
        .info-table th {
            width: 25%;
            background-color: #f8f9fa;
        }
        .info-table td {
            width: 25%;
        }
        .signature-box {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Floating Print Button (Hidden in Print) -->
    <div class="text-center my-4 no-print">
        <button class="btn btn-primary btn-lg px-4" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-printer me-2" viewBox="0 0 16 16">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
            </svg>
            Print Profile
        </button>
    </div>

    <div class="print-container">
        <!-- School Header -->
        <div class="school-header d-flex justify-content-between align-items-center">
            @if(isset($siteSetting) && $siteSetting->logo)
                <img src="{{ asset('storage/' . $siteSetting->logo) }}" alt="Logo" style="height: 80px;">
            @else
                <div style="width: 80px; height: 80px; background: #eee; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 10px; color: #888;">No Logo</span>
                </div>
            @endif
            
            <div class="flex-grow-1 text-center px-3">
                <h2>{{ collect(explode(' ', $siteSetting->title ?? 'Green Peace Lincoln College'))->take(4)->join(' ') }}</h2>
                <p>{{ $siteSetting->location ?? 'Itahari, Nepal' }} | Phone: {{ $siteSetting->phone ?? '+977 1234567890' }}</p>
                <p>{{ $siteSetting->email ?? 'info@gplc.edu.np' }}</p>
            </div>
            
            <div style="width: 80px;">
                <!-- Placeholder for balance / symmetry -->
            </div>
        </div>

        <h4 class="text-center fw-bold mb-4" style="text-decoration: underline;">STUDENT PROFILE</h4>

        <!-- Basic Info & Photo -->
        <div class="row mb-4">
            <div class="col-9">
                <table class="table table-sm table-bordered info-table">
                    <tbody>
                        <tr>
                            <th>Admission No.</th>
                            <td><strong>{{ $student->admission_no }}</strong></td>
                            <th>Admission Date</th>
                            <td>{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Full Name</th>
                            <td colspan="3" class="fw-bold text-uppercase">{{ $student->full_name }}</td>
                        </tr>
                        @php
                            $current = $student->currentEnrollment;
                        @endphp
                        <tr>
                            <th>Current Class</th>
                            <td>{{ $current->academicClass->name ?? 'N/A' }}</td>
                            <th>Section</th>
                            <td>{{ $current->section->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Academic Year</th>
                            <td>{{ $current->academicYear->name ?? 'N/A' }}</td>
                            <th>Roll No.</th>
                            <td>{{ $current->roll_no ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-3 text-end">
                @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" class="student-photo" alt="Student Photo">
                @else
                    <div class="student-photo d-inline-flex align-items-center justify-content-center bg-light text-muted">
                        <span>Photo</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Personal Details -->
        <div class="section-title">Personal Details</div>
        <table class="table table-sm table-bordered info-table mb-4">
            <tbody>
                <tr>
                    <th>Gender</th>
                    <td>{{ $student->gender ?? 'N/A' }}</td>
                    <th>Date of Birth</th>
                    <td>{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d M, Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Blood Group</th>
                    <td>{{ $student->blood_group ?? 'N/A' }}</td>
                    <th>Religion</th>
                    <td>{{ $student->religion ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td colspan="3">{{ $student->category ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Current Address</th>
                    <td colspan="3">{{ $student->current_address ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Permanent Address</th>
                    <td colspan="3">{{ $student->permanent_address ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Guardian Details -->
        <div class="section-title">Guardian Details</div>
        @if($student->guardian)
        <table class="table table-sm table-bordered info-table mb-4">
            <tbody>
                <tr>
                    <th>Father's Name</th>
                    <td>{{ $student->guardian->father_name ?? 'N/A' }}</td>
                    <th>Father's Phone</th>
                    <td>{{ $student->guardian->father_phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Mother's Name</th>
                    <td>{{ $student->guardian->mother_name ?? 'N/A' }}</td>
                    <th>Mother's Phone</th>
                    <td>{{ $student->guardian->mother_phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th colspan="4" class="bg-light text-center">Local Guardian Details (if any)</th>
                </tr>
                <tr>
                    <th>Guardian Name</th>
                    <td>{{ $student->guardian->guardian_name ?? 'N/A' }}</td>
                    <th>Relation</th>
                    <td>{{ $student->guardian->guardian_relation ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Guardian Phone</th>
                    <td>{{ $student->guardian->guardian_phone ?? 'N/A' }}</td>
                    <th>Guardian Email</th>
                    <td>{{ $student->guardian->guardian_email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Guardian Address</th>
                    <td colspan="3">{{ $student->guardian->guardian_address ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
        @else
            <p class="text-muted">No guardian information available.</p>
        @endif

        <!-- Previous School -->
        @if($student->previous_school_details)
        <div class="section-title">Previous Academic Details</div>
        <div class="border p-3 mb-4" style="background: #f8f9fa;">
            {{ $student->previous_school_details }}
        </div>
        @endif

        <!-- Signatures -->
        <div class="signature-box mt-5 pt-5">
            <div class="signature-line">
                Signature of Parent / Guardian
            </div>
            <div class="signature-line">
                Signature of Principal / Admin
            </div>
        </div>

    </div>
</body>
</html>
