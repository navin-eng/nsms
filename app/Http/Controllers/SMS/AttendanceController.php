<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\Enrollment;
use App\Models\StudentAttendance;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Pratiksh\Nepalidate\Services\NepaliDate;
use Pratiksh\Nepalidate\Services\EnglishDate;

class AttendanceController extends Controller
{
    /**
     * Display the attendance marking interface.
     */
    public function index(Request $request)
    {
        $classes = AcademicClass::all();
        $sections = Section::with('academicClasses')->orderBy('name')->get();
        $students = [];
        
        $calendarService = app(\App\Services\CalendarService::class);
        $calendarSystem = $calendarService->system();
        
        $defaultDateAD = date('Y-m-d');
        $defaultDateDisplay = $calendarService->displayDate($defaultDateAD);

        $inputDate = $request->input('date', $defaultDateDisplay);
        
        $selectedDateDisplay = $inputDate;
        $selectedDateAD = $calendarService->toDbDate($inputDate)?->toDateString() ?? $defaultDateAD;

        $selectedClassId = $request->input('academic_class_id');
        $selectedSectionId = $request->input('section_id');

        if ($selectedClassId && $selectedSectionId) {
            // Get all students enrolled in the selected class and section
            $students = Enrollment::with(['student', 'academicYear', 'academicClass', 'section'])
                ->where('academic_class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->where('status', 'Continuing')
                ->get()
                ->map(function ($enrollment) use ($selectedDateAD) {
                    // Check if attendance already exists for this date
                    $searchDate = \Carbon\Carbon::parse($selectedDateAD)->format('Y-m-d 00:00:00');
                    $attendance = StudentAttendance::where('student_id', $enrollment->student_id)
                        ->where('date', $searchDate)
                        ->first();
                        
                    $enrollment->attendance_status = $attendance ? $attendance->status : 'Present'; // Default Present
                    $enrollment->attendance_remarks = $attendance ? $attendance->remarks : '';
                    return $enrollment;
                });
        }

        return view('backend.pages.sms.attendance.index', compact(
            'classes', 'sections', 'students', 'selectedDateDisplay', 'selectedDateAD', 'calendarSystem', 'selectedClassId', 'selectedSectionId'
        ));
    }

    /**
     * Store or update attendance records in batch.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'required|exists:sections,id',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:Present,Absent,Late,Half-Day',
        ]);

        $date = $request->date;
        $searchDate = \Carbon\Carbon::parse($date)->format('Y-m-d 00:00:00');
        
        DB::beginTransaction();
        try {
            foreach ($request->attendance as $studentId => $data) {
                // We need the academic_year_id to store. We can get it from the enrollment.
                $enrollment = Enrollment::where('student_id', $studentId)
                    ->where('academic_class_id', $request->academic_class_id)
                    ->where('section_id', $request->section_id)
                    ->latest()
                    ->first();

                if ($enrollment) {
                    $previousAttendance = StudentAttendance::where('student_id', $studentId)
                        ->where('date', $searchDate)
                        ->first();
                        
                    $wasAbsentBefore = $previousAttendance && $previousAttendance->status === 'Absent';
                    $isAbsentNow = $data['status'] === 'Absent';

                    StudentAttendance::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'date' => $searchDate,
                        ],
                        [
                            'academic_year_id' => $enrollment->academic_year_id,
                            'academic_class_id' => $request->academic_class_id,
                            'section_id' => $request->section_id,
                            'status' => $data['status'],
                            'remarks' => $data['remarks'] ?? null,
                        ]
                    );

                    // Send alert if newly marked as Absent
                    if ($isAbsentNow && !$wasAbsentBefore) {
                        $student = \App\Models\Student::with('guardian')->find($studentId);
                        if ($student) {
                            $template = \App\Models\CommunicationTemplate::where('name', 'Student Absence Alert')
                                ->where('is_active', true)
                                ->first();

                            $studentName = $student->full_name;
                            $formattedDate = \Carbon\Carbon::parse($date)->format('M d, Y');
                            
                            $msgSubject = $template ? $template->subject : 'Student Absence Notification';
                            $msgBody = $template ? $template->body : 'Dear Parent, your child {student_name} was marked absent today ({date}).';

                            // Replace placeholders
                            $msgBody = str_replace(
                                ['{student_name}', '{date}'],
                                [$studentName, $formattedDate],
                                $msgBody
                            );

                            $commService = app(\App\Services\Communication\CommunicationService::class);
                            
                            // Send to Guardian via SMS & Email/Push depending on template or defaults
                            $channels = $template ? explode(',', $template->type) : ['sms', 'email'];
                            foreach ($channels as $channel) {
                                $channel = trim($channel);
                                try {
                                    $commService->dispatch($channel, $student, $msgBody, $msgSubject);
                                } catch (\Exception $ex) {
                                    \Illuminate\Support\Facades\Log::error("Failed to send absence alert via {$channel}: " . $ex->getMessage());
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();
            
            $displayDate = system_date($date);
            
            return back()->with('success', 'Attendance saved successfully for ' . $displayDate);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error saving attendance: ' . $e->getMessage());
        }
    }

    /**
     * Display attendance report grid.
     */
    public function report(Request $request)
    {
        $classes = AcademicClass::all();
        $sections = Section::with('academicClasses')->orderBy('name')->get();
        
        $reportType = $request->input('report_type', 'monthly_grid');
        
        $calendarService = app(\App\Services\CalendarService::class);
        $calendarSystem = $calendarService->system();
        
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        if ($calendarSystem === 'BS') {
            $currentBS = $calendarService->displayDate(date('Y-m-d'));
            $parts = explode('-', $currentBS);
            if (count($parts) >= 2) {
                $currentYear = $parts[0];
                $currentMonth = $parts[1];
            }
        }

        $month = sprintf("%02d", $request->input('month', $currentMonth));
        $year = $request->input('year', $currentYear);
        
        // For range report
        $startDateDisplay = $request->input('start_date');
        $endDateDisplay = $request->input('end_date');
        
        $startDateAD = $startDateDisplay ? $calendarService->toDbDate($startDateDisplay)?->toDateString() : null;
        $endDateAD = $endDateDisplay ? $calendarService->toDbDate($endDateDisplay)?->toDateString() : null;
        
        $selectedClassId = $request->input('academic_class_id');
        $selectedSectionId = $request->input('section_id');

        $reportData = [];
        
        if ($calendarSystem === 'BS') {
            $daysInMonth = 32;
            while ($daysInMonth >= 29) {
                try {
                    EnglishDate::create("$year-$month-$daysInMonth")->toAD();
                    break;
                } catch (\Exception $e) {
                    $daysInMonth--;
                }
            }
        } else {
            $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        }
        
        $monthName = '';
        if ($calendarSystem === 'BS') {
            $bsMonths = [1=>'Baishakh', 2=>'Jestha', 3=>'Ashadh', 4=>'Shrawan', 5=>'Bhadra', 6=>'Ashwin', 7=>'Kartik', 8=>'Mangsir', 9=>'Poush', 10=>'Magh', 11=>'Falgun', 12=>'Chaitra'];
            $monthName = $bsMonths[(int)$month];
        } else {
            $monthName = date('F', mktime(0, 0, 0, (int)$month, 1));
        }

        if ($selectedClassId && $selectedSectionId) {
            $enrollments = Enrollment::with(['student', 'academicYear'])
                ->where('academic_class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->where('status', 'Continuing')
                ->get();
                
            foreach ($enrollments as $enrollment) {
                $studentId = $enrollment->student_id;
                
                // Determine Date Range based on report type
                $queryStartDateAD = null;
                $queryEndDateAD = null;
                
                if (in_array($reportType, ['monthly_grid', 'total_present', 'highest_present_month', 'highest_absent_month'])) {
                    if ($calendarSystem === 'BS') {
                        $queryStartDateAD = EnglishDate::create("$year-$month-01")->toAD();
                        $queryEndDateAD = EnglishDate::create("$year-$month-$daysInMonth")->toAD();
                    } else {
                        $queryStartDateAD = "$year-$month-01";
                        $queryEndDateAD = "$year-$month-$daysInMonth";
                    }
                } elseif ($reportType === 'range_absent') {
                    $queryStartDateAD = $startDateAD;
                    $queryEndDateAD = $endDateAD;
                } elseif (in_array($reportType, ['highest_present_year', 'highest_absent_year'])) {
                    // Use academic year range
                    $queryStartDateAD = $enrollment->academicYear->start_date;
                    $queryEndDateAD = $enrollment->academicYear->end_date;
                }
                
                if ($queryStartDateAD && $queryEndDateAD) {
                    $attendancesQuery = StudentAttendance::where('student_id', $studentId)
                        ->whereBetween('date', [$queryStartDateAD, $queryEndDateAD])
                        ->get();
                } else {
                    $attendancesQuery = collect([]);
                }
                
                $attendances = $attendancesQuery->keyBy(function($item) use ($calendarSystem) {
                    if ($calendarSystem === 'BS') {
                        $bsDate = NepaliDate::create(Carbon::parse($item->date))->toBS();
                        $parts = explode('-', $bsDate);
                        return (int)$parts[2]; // get the day as integer
                    }
                    return Carbon::parse($item->date)->format('j');
                });
                    
                $summary = [
                    'P' => $attendancesQuery->where('status', 'Present')->count(),
                    'A' => $attendancesQuery->where('status', 'Absent')->count(),
                    'L' => $attendancesQuery->where('status', 'Late')->count(),
                    'HD' => $attendancesQuery->where('status', 'Half-Day')->count(),
                ];
                
                if ($reportType === 'range_absent') {
                    // Only include students who have at least one absent in this range
                    if ($summary['A'] > 0) {
                        $reportData[] = [
                            'student' => $enrollment->student,
                            'roll_no' => $enrollment->roll_no,
                            'absences' => $attendancesQuery->where('status', 'Absent'),
                            'total_absent' => $summary['A']
                        ];
                    }
                } else {
                    $reportData[] = [
                        'student' => $enrollment->student,
                        'roll_no' => $enrollment->roll_no,
                        'attendances' => $attendances,
                        'summary' => $summary
                    ];
                }
            }
        }
        
        // Sorting logic based on report type
        if (in_array($reportType, ['highest_present_month', 'highest_present_year'])) {
            usort($reportData, function($a, $b) {
                return $b['summary']['P'] <=> $a['summary']['P'];
            });
        } elseif (in_array($reportType, ['highest_absent_month', 'highest_absent_year'])) {
            usort($reportData, function($a, $b) {
                return $b['summary']['A'] <=> $a['summary']['A'];
            });
        } elseif ($reportType === 'range_absent') {
            usort($reportData, function($a, $b) {
                return $b['total_absent'] <=> $a['total_absent'];
            });
        } else {
            // Sort by roll no
            usort($reportData, function($a, $b) {
                return $a['roll_no'] <=> $b['roll_no'];
            });
        }

        if ($request->input('print') == 'true') {
            return view('backend.pages.sms.attendance.print', compact(
                'classes', 'sections', 'month', 'year', 'selectedClassId', 'selectedSectionId', 'reportData', 'daysInMonth', 'calendarSystem', 'monthName', 'reportType', 'startDateDisplay', 'endDateDisplay'
            ));
        }

        return view('backend.pages.sms.attendance.report', compact(
            'classes', 'sections', 'month', 'year', 'selectedClassId', 'selectedSectionId', 'reportData', 'daysInMonth', 'calendarSystem', 'monthName', 'reportType', 'startDateDisplay', 'endDateDisplay'
        ));
    }
}
