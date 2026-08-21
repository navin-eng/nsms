<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Application - {{ $application->first_name }} {{ $application->last_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .document-title {
            font-size: 18px;
            text-transform: uppercase;
            margin-top: 10px;
            letter-spacing: 2px;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 8px;
            font-weight: bold;
            border-left: 4px solid #333;
            margin-bottom: 15px;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 8px 0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 35%;
        }
        .value {
            border-bottom: 1px dotted #ccc;
            width: 65%;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
        @media print {
            body { padding: 0; }
            button { display: none; }
        }
    </style>
</head>
<body>

    <div style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Print Document</button>
    </div>

    <div class="container">
        <div class="header">
            <h1 class="school-name">School Management System</h1>
            <div class="document-title">Admission Application Form</div>
            <div style="margin-top: 10px; font-size: 14px;">Application ID: #{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }} | Date: {{ $application->application_date->format('d M, Y') }}</div>
        </div>

        <div class="section-title">1. Applicant Information</div>
        <table>
            <tr>
                <td class="label">Full Name:</td>
                <td class="value">{{ $application->first_name }} {{ $application->last_name }}</td>
            </tr>
            <tr>
                <td class="label">Date of Birth:</td>
                <td class="value">{{ $application->dob->format('d M, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Gender:</td>
                <td class="value">{{ $application->gender }}</td>
            </tr>
        </table>

        <div class="section-title">2. Academic Details</div>
        <table>
            <tr>
                <td class="label">Applying For Class:</td>
                <td class="value">{{ $application->academicClass->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Academic Year:</td>
                <td class="value">{{ $application->academicYear->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Previous School:</td>
                <td class="value">{{ $application->previous_school ?: 'None' }}</td>
            </tr>
        </table>

        <div class="section-title">3. Parent / Guardian Details</div>
        <table>
            <tr>
                <td class="label">Father's Name:</td>
                <td class="value">{{ $application->father_name ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Mother's Name:</td>
                <td class="value">{{ $application->mother_name ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Contact Number:</td>
                <td class="value">{{ $application->contact_number }}</td>
            </tr>
        </table>
        
        <div class="section-title">4. Application Status</div>
        <table>
            <tr>
                <td class="label">Current Status:</td>
                <td class="value" style="font-weight: bold;">{{ strtoupper($application->status) }}</td>
            </tr>
        </table>

        <div class="footer">
            <div class="signature-box">
                <div class="signature-line">Parent/Guardian Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized Signatory</div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Automatically open print dialog
            setTimeout(() => { window.print(); }, 500);
        };
    </script>
</body>
</html>
