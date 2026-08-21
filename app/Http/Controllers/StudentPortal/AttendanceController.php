<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Pratiksh\Nepalidate\Services\NepaliDate;
use Pratiksh\Nepalidate\Services\EnglishDate;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $calendarSystem = SiteSetting::current()->calendar_system ?? 'AD';
        
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        if ($calendarSystem === 'BS') {
            $currentBS = NepaliDate::create(Carbon::now())->toBS();
            $parts = explode('-', $currentBS);
            $currentYear = $parts[0];
            $currentMonth = $parts[1];
        }

        $month = sprintf("%02d", $request->month ?? $currentMonth);
        $year = $request->year ?? $currentYear;
        
        $startDateAD = null;
        $endDateAD = null;
        $totalDays = 30;

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
            $totalDays = $daysInMonth;
            
            $startDateAD = EnglishDate::create("$year-$month-01")->toAD();
            $endDateAD = EnglishDate::create("$year-$month-$daysInMonth")->toAD();
        } else {
            $startDateAD = "$year-$month-01";
            $totalDays = Carbon::create($year, $month)->daysInMonth;
            $endDateAD = "$year-$month-$totalDays";
        }

        $attendances = StudentAttendance::where('student_id', $student->id)
            ->whereBetween('date', [$startDateAD, $endDateAD])
            ->orderBy('date', 'asc')
            ->get();

        $stats = [
            'present' => $attendances->where('status', 'Present')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'half_day' => $attendances->where('status', 'Half Day')->count(),
        ];

        // Month names for display
        $monthName = '';
        if ($calendarSystem === 'BS') {
            $bsMonths = [1=>'Baishakh', 2=>'Jestha', 3=>'Ashadh', 4=>'Shrawan', 5=>'Bhadra', 6=>'Ashwin', 7=>'Kartik', 8=>'Mangsir', 9=>'Poush', 10=>'Magh', 11=>'Falgun', 12=>'Chaitra'];
            $monthName = $bsMonths[(int)$month];
        } else {
            $monthName = date('F', mktime(0, 0, 0, (int)$month, 1));
        }

        return view('student.attendance', compact('student', 'attendances', 'month', 'year', 'totalDays', 'stats', 'calendarSystem', 'monthName'));
    }
}
