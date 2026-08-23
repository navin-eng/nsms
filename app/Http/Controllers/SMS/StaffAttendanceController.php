<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Department;
use App\Models\StaffAttendance;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Pratiksh\Nepalidate\Services\NepaliDate;
use Pratiksh\Nepalidate\Services\EnglishDate;

class StaffAttendanceController extends Controller
{
    /**
     * Display the staff attendance marking interface.
     */
    public function index(Request $request)
    {
        $departments = Department::all();
        $staffMembers = [];

        $calendarService = app(\App\Services\CalendarService::class);
        $calendarSystem = $calendarService->system();

        $defaultDateAD = date('Y-m-d');
        $defaultDateDisplay = $calendarService->displayDate($defaultDateAD);

        $inputDate = $request->input('date', $defaultDateDisplay);

        $selectedDateDisplay = $inputDate;
        $selectedDateAD = $calendarService->toDbDate($inputDate)?->toDateString() ?? $defaultDateAD;

        $selectedDepartmentId = $request->input('department_id');

        if ($selectedDepartmentId) {
            $staffMembers = Staff::with('department', 'designation')
                ->where('department_id', $selectedDepartmentId)
                ->where('status', 'Active')
                ->get()
                ->map(function ($staff) use ($selectedDateAD) {
                    $searchDate = Carbon::parse($selectedDateAD)->format('Y-m-d 00:00:00');
                    $attendance = StaffAttendance::where('staff_id', $staff->id)
                        ->where('date', $searchDate)
                        ->first();

                    $staff->attendance_status = $attendance ? $attendance->status : 'Present';
                    $staff->attendance_remarks = $attendance ? $attendance->remarks : '';
                    return $staff;
                });
        }

        return view('backend.pages.sms.staff-attendance.index', compact(
            'departments',
            'staffMembers',
            'selectedDateDisplay',
            'selectedDateAD',
            'calendarSystem',
            'selectedDepartmentId'
        ));
    }

    /**
     * Store or update staff attendance records.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:Present,Absent,Late,Half-Day',
        ]);

        $date = $request->date;
        $searchDate = Carbon::parse($date)->format('Y-m-d 00:00:00');

        DB::beginTransaction();
        try {
            foreach ($request->attendance as $staffId => $data) {
                StaffAttendance::updateOrCreate(
                    [
                        'staff_id' => $staffId,
                        'date' => $searchDate,
                    ],
                    [
                        'status' => $data['status'],
                        'remarks' => $data['remarks'] ?? null,
                    ]
                );
            }

            DB::commit();

            $displayDate = system_date($date);

            return back()->with('success', 'Staff Attendance saved successfully for ' . $displayDate);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error saving staff attendance: ' . $e->getMessage());
        }
    }

    /**
     * Generate staff attendance reports.
     */
    public function report(Request $request)
    {
        $departments = Department::all();
        $calendarService = app(\App\Services\CalendarService::class);
        $calendarSystem = $calendarService->system();

        // Default to current month/year
        $currentMonth = date('m');
        $currentYear = date('Y');

        if ($calendarSystem === 'BS') {
            $bsDate = $calendarService->displayDate(date('Y-m-d'));
            $parts = explode('-', $bsDate);
            if (count($parts) >= 2) {
                $currentYear = $parts[0];
                $currentMonth = $parts[1];
            }
        }

        $month = sprintf("%02d", $request->input('month', $currentMonth));
        $year = $request->input('year', $currentYear);

        $startDateDisplay = $request->input('start_date');
        $endDateDisplay = $request->input('end_date');
        
        $startDateAD = $startDateDisplay ? $calendarService->toDbDate($startDateDisplay)?->toDateString() : null;
        $endDateAD = $endDateDisplay ? $calendarService->toDbDate($endDateDisplay)?->toDateString() : null;

        $selectedDepartmentId = $request->input('department_id');
        $reportType = $request->input('report_type', 'monthly_grid');

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
            $bsMonths = [1 => 'Baishakh', 2 => 'Jestha', 3 => 'Ashadh', 4 => 'Shrawan', 5 => 'Bhadra', 6 => 'Ashwin', 7 => 'Kartik', 8 => 'Mangsir', 9 => 'Poush', 10 => 'Magh', 11 => 'Falgun', 12 => 'Chaitra'];
            $monthName = $bsMonths[(int) $month];
        } else {
            $monthName = date('F', mktime(0, 0, 0, (int) $month, 1));
        }

        if ($selectedDepartmentId) {
            $staffMembers = Staff::with('designation')
                ->where('department_id', $selectedDepartmentId)
                ->where('status', 'Active')
                ->get();

            foreach ($staffMembers as $staff) {
                $queryStartDateAD = null;
                $queryEndDateAD = null;

                if (in_array($reportType, ['monthly_grid', 'total_present', 'highest_present_month', 'highest_absent_month'])) {
                    if ($calendarSystem === 'BS') {
                        $queryStartDateAD = EnglishDate::create("$year-$month-01")->toAD();
                        $queryEndDateAD = EnglishDate::create("$year-$month-$daysInMonth")->toAD();
                    } else {
                        $queryStartDateAD = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
                        $queryEndDateAD = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
                    }
                } elseif (in_array($reportType, ['highest_present_year', 'highest_absent_year'])) {
                    if ($calendarSystem === 'BS') {
                        $queryStartDateAD = EnglishDate::create("$year-01-01")->toAD();
                        $queryEndDateAD = EnglishDate::create("$year-12-30")->toAD(); // Approx end
                    } else {
                        $queryStartDateAD = Carbon::createFromDate($year, 1, 1)->startOfYear()->format('Y-m-d');
                        $queryEndDateAD = Carbon::createFromDate($year, 1, 1)->endOfYear()->format('Y-m-d');
                    }
                } elseif ($reportType === 'range_absent') {
                    $queryStartDateAD = $startDateAD;
                    $queryEndDateAD = $endDateAD;
                }

                $query = StaffAttendance::where('staff_id', $staff->id)
                    ->where('date', '>=', Carbon::parse($queryStartDateAD)->format('Y-m-d 00:00:00'))
                    ->where('date', '<=', Carbon::parse($queryEndDateAD)->format('Y-m-d 23:59:59'));

                if ($reportType === 'range_absent') {
                    $absences = $query->where('status', 'Absent')->orderBy('date', 'asc')->get();

                    if ($absences->count() > 0) {
                        $reportData[] = [
                            'staff' => $staff,
                            'total_absent' => $absences->count(),
                            'absences' => $absences
                        ];
                    }
                } else {
                    $attendances = $query->get();
                    $attendanceMap = collect();

                    if (in_array($reportType, ['monthly_grid', 'total_present', 'highest_present_month', 'highest_absent_month'])) {
                        foreach ($attendances as $att) {
                            $attDateAD = Carbon::parse($att->date)->format('Y-m-d');
                            $day = $calendarSystem === 'BS'
                                ? (int) explode('-', NepaliDate::create(Carbon::parse($attDateAD))->toBS())[2]
                                : (int) Carbon::parse($attDateAD)->format('d');
                            $attendanceMap->put((string) $day, $att);
                        }
                    }

                    $totalP = $attendances->where('status', 'Present')->count();
                    $totalA = $attendances->where('status', 'Absent')->count();
                    $totalL = $attendances->where('status', 'Late')->count();
                    $totalHD = $attendances->where('status', 'Half-Day')->count();

                    $reportData[] = [
                        'staff' => $staff,
                        'attendances' => $attendanceMap,
                        'summary' => [
                            'P' => $totalP,
                            'A' => $totalA,
                            'L' => $totalL,
                            'HD' => $totalHD,
                        ]
                    ];
                }
            }

            if ($reportType === 'highest_present_month' || $reportType === 'highest_present_year') {
                usort($reportData, fn($a, $b) => $b['summary']['P'] <=> $a['summary']['P']);
            } elseif ($reportType === 'highest_absent_month' || $reportType === 'highest_absent_year') {
                usort($reportData, fn($a, $b) => $b['summary']['A'] <=> $a['summary']['A']);
            } elseif ($reportType === 'range_absent') {
                usort($reportData, fn($a, $b) => $b['total_absent'] <=> $a['total_absent']);
            }
        }

        if ($request->input('print') == 'true') {
            return view('backend.pages.sms.staff-attendance.print', compact(
                'departments',
                'month',
                'year',
                'selectedDepartmentId',
                'reportData',
                'daysInMonth',
                'calendarSystem',
                'monthName',
                'reportType',
                'startDateDisplay',
                'endDateDisplay'
            ));
        }

        return view('backend.pages.sms.staff-attendance.report', compact(
            'departments',
            'month',
            'year',
            'selectedDepartmentId',
            'reportData',
            'daysInMonth',
            'calendarSystem',
            'monthName',
            'reportType',
            'startDateDisplay',
            'endDateDisplay'
        ));
    }
}
