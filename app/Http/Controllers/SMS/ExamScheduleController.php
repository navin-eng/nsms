<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $exams = \App\Models\Exam::latest()->get();
        $classes = \App\Models\AcademicClass::orderBy('level')->get();
        
        $selectedExam = null;
        $selectedClass = null;
        $subjects = collect();
        $schedules = collect();

        if ($request->has('exam_id') && $request->has('academic_class_id')) {
            $selectedExam = \App\Models\Exam::find($request->exam_id);
            $selectedClass = \App\Models\AcademicClass::find($request->academic_class_id);
            
            if ($selectedClass) {
                $subjects = $selectedClass->subjects()->orderBy('order_level')->get();
                $schedules = \App\Models\ExamSchedule::where('exam_id', $selectedExam->id)
                    ->where('academic_class_id', $selectedClass->id)
                    ->get()
                    ->keyBy('subject_id');
            }
        }

        return view('backend.pages.sms.exams.schedules.index', compact('exams', 'classes', 'selectedExam', 'selectedClass', 'subjects', 'schedules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'schedules' => 'required|array'
        ]);

        $examId = $request->exam_id;
        $classId = $request->academic_class_id;

        foreach ($request->schedules as $subjectId => $data) {
            if (isset($data['include']) && $data['include'] == '1') {
                \App\Models\ExamSchedule::updateOrCreate(
                    [
                        'exam_id' => $examId,
                        'academic_class_id' => $classId,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'exam_date' => $data['exam_date'] ?? null,
                        'start_time' => $data['start_time'] ?? null,
                        'end_time' => $data['end_time'] ?? null,
                        'theory_full_marks' => $data['theory_full_marks'] ?? 0,
                        'theory_pass_marks' => $data['theory_pass_marks'] ?? 0,
                        'practical_full_marks' => $data['practical_full_marks'] ?? 0,
                        'practical_pass_marks' => $data['practical_pass_marks'] ?? 0,
                    ]
                );
            } else {
                // If not included, delete if exists
                \App\Models\ExamSchedule::where('exam_id', $examId)
                    ->where('academic_class_id', $classId)
                    ->where('subject_id', $subjectId)
                    ->delete();
            }
        }

        return back()->with('success', 'Exam schedule saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
