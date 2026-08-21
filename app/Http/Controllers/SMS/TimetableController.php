<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Period;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    public function index(Request $request)
    {
        $years   = AcademicYear::orderByDesc('start_date')->get();
        $classes = AcademicClass::orderBy('numeric_value')->get();
        $periods = Period::orderBy('sort_order')->get();

        $entries   = collect();
        $conflicts = collect();
        $sections  = collect();
        $subjects  = collect();
        $teachers  = collect();

        $selectedYear    = $request->academic_year_id;
        $selectedClass   = $request->academic_class_id;
        $selectedSection = $request->section_id;

        if ($selectedClass) {
            $sections = Section::whereHas('academicClasses', fn($q) => $q->where('academic_classes.id', $selectedClass))->get();
            $subjects = Subject::orderBy('name')->get();

            // Teachers: staff who are teachers with role-based filtering
            $teacherRoleUserIds = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', 'Teacher')
                ->pluck('model_has_roles.model_id');

            $teachers = Staff::whereIn('user_id', $teacherRoleUserIds)->get();
        }

        if ($selectedYear && $selectedClass) {
            $query = TimetableEntry::with(['period', 'subject', 'staff', 'section'])
                ->where('academic_year_id', $selectedYear)
                ->where('academic_class_id', $selectedClass);

            if ($selectedSection) {
                $query->where(function ($q) use ($selectedSection) {
                    $q->where('section_id', $selectedSection)->orWhereNull('section_id');
                });
            }

            $entries = $query->get()->keyBy(fn($e) => $e->period_id . '_' . $e->day_of_week);

            // Detect conflicts: same teacher, same period, same day, different class
            if ($entries->isNotEmpty()) {
                $entryIds = $entries->pluck('id');
                $conflicts = TimetableEntry::whereIn('id', $entryIds)
                    ->whereNotNull('staff_id')
                    ->get()
                    ->groupBy(fn($e) => $e->staff_id . '_' . $e->period_id . '_' . $e->day_of_week)
                    ->filter(fn($group) => $group->count() > 1)
                    ->flatMap(fn($group) => $group->pluck('id'))
                    ->unique();
            }
        }

        return view('backend.pages.sms.timetable.index', compact(
            'years', 'classes', 'periods', 'sections', 'subjects', 'teachers',
            'entries', 'conflicts',
            'selectedYear', 'selectedClass', 'selectedSection'
        ));
    }

    public function save(Request $request)
    {
        $request->validate([
            'academic_year_id'  => 'required|exists:academic_years,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'section_id'        => 'nullable|exists:sections,id',
            'entries'           => 'nullable|array',
        ]);

        $yearId    = $request->academic_year_id;
        $classId   = $request->academic_class_id;
        $sectionId = $request->section_id ?: null;

        DB::beginTransaction();
        try {
            // Delete existing for this class/section/year
            TimetableEntry::where('academic_year_id', $yearId)
                ->where('academic_class_id', $classId)
                ->where('section_id', $sectionId)
                ->delete();

            $entries = $request->input('entries', []);

            foreach ($entries as $key => $entry) {
                // key format: period_id_DayOfWeek
                [$periodId, $day] = explode('_', $key, 2);

                if (empty($entry['subject_id'])) continue;

                // Conflict check: teacher already assigned elsewhere this period/day
                if (!empty($entry['staff_id'])) {
                    $conflict = TimetableEntry::where('academic_year_id', $yearId)
                        ->where('staff_id', $entry['staff_id'])
                        ->where('period_id', $periodId)
                        ->where('day_of_week', $day)
                        ->where('academic_class_id', '!=', $classId)
                        ->exists();

                    if ($conflict) {
                        DB::rollBack();
                        return back()->with('error', "Conflict detected! The teacher for {$day} Period {$periodId} is already assigned to another class at the same time.")->withInput();
                    }
                }

                TimetableEntry::create([
                    'academic_year_id'  => $yearId,
                    'academic_class_id' => $classId,
                    'section_id'        => $sectionId,
                    'period_id'         => $periodId,
                    'day_of_week'       => $day,
                    'subject_id'        => $entry['subject_id'],
                    'staff_id'          => $entry['staff_id'] ?: null,
                    'room'              => $entry['room'] ?? null,
                    'custom_start_time' => !empty($entry['custom_start_time']) ? $entry['custom_start_time'] : null,
                    'custom_end_time'   => !empty($entry['custom_end_time'])   ? $entry['custom_end_time']   : null,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Timetable saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error saving timetable: ' . $e->getMessage())->withInput();
        }
    }

    public function teacher(Request $request)
    {
        $teacherRoleUserIds = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'Teacher')
            ->pluck('model_has_roles.model_id');

        $teachers = Staff::whereIn('user_id', $teacherRoleUserIds)->get();
        $years    = AcademicYear::orderByDesc('start_date')->get();
        $periods  = Period::orderBy('sort_order')->get();
        $entries  = collect();

        $selectedTeacher = $request->staff_id;
        $selectedYear    = $request->academic_year_id;

        if ($selectedTeacher && $selectedYear) {
            $entries = TimetableEntry::with(['period', 'subject', 'academicClass', 'section'])
                ->where('staff_id', $selectedTeacher)
                ->where('academic_year_id', $selectedYear)
                ->get()
                ->keyBy(fn($e) => $e->period_id . '_' . $e->day_of_week);
        }

        return view('backend.pages.sms.timetable.teacher', compact(
            'teachers', 'years', 'periods', 'entries', 'selectedTeacher', 'selectedYear'
        ));
    }
}
