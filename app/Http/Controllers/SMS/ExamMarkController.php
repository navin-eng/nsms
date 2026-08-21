<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamMarkController extends Controller
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
        $selectedSubject = null;
        
        $students = collect();
        $schedule = null;
        $marks = collect();
        $subjects = collect();

        if ($request->has('exam_id') && $request->has('academic_class_id')) {
            $selectedExam = \App\Models\Exam::find($request->exam_id);
            $selectedClass = \App\Models\AcademicClass::find($request->academic_class_id);
            
            if ($selectedClass) {
                // Get subjects that have an exam schedule for this class/exam
                $subjectIds = \App\Models\ExamSchedule::where('exam_id', $selectedExam->id)
                    ->where('academic_class_id', $selectedClass->id)
                    ->pluck('subject_id');
                
                $subjects = \App\Models\Subject::whereIn('id', $subjectIds)->orderBy('order_level')->get();
                
                if ($request->has('subject_id')) {
                    $selectedSubject = \App\Models\Subject::find($request->subject_id);
                    $schedule = \App\Models\ExamSchedule::where('exam_id', $selectedExam->id)
                        ->where('academic_class_id', $selectedClass->id)
                        ->where('subject_id', $selectedSubject->id)
                        ->first();
                        
                    // Load active students in this class
                    $students = \App\Models\Student::whereHas('enrollments', function($q) use ($selectedClass) {
                        $q->where('academic_class_id', $selectedClass->id)
                          ->whereIn('status', ['Continuing', 'Promoted', 'New']);
                    })->orderBy('first_name')->get();
                    
                    // Load existing marks
                    $marks = \App\Models\ExamMark::where('exam_id', $selectedExam->id)
                        ->where('subject_id', $selectedSubject->id)
                        ->get()
                        ->keyBy('student_id');
                }
            }
        }

        return view('backend.pages.sms.exams.marks.index', compact(
            'exams', 'classes', 'subjects', 'selectedExam', 'selectedClass', 'selectedSubject', 'students', 'schedule', 'marks'
        ));
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
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|array'
        ]);

        $examId = $request->exam_id;
        $subjectId = $request->subject_id;

        foreach ($request->marks as $studentId => $data) {
            $isAbsent = isset($data['is_absent']) && $data['is_absent'] == '1';
            
            \App\Models\ExamMark::updateOrCreate(
                [
                    'exam_id' => $examId,
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                ],
                [
                    'theory_marks' => $isAbsent ? 0 : ($data['theory_marks'] ?? 0),
                    'practical_marks' => $isAbsent ? 0 : ($data['practical_marks'] ?? 0),
                    'is_absent' => $isAbsent,
                    'remarks' => $data['remarks'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Marks saved successfully.');
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
