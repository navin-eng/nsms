<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeworkController extends Controller
{
    public function index()
    {
        $homeworks = Homework::with(['academicClass', 'section', 'subject', 'submissions'])->latest()->get();
        $classes = AcademicClass::all();
        $sections = Section::with('academicClasses')->orderBy('name')->get();
        $subjects = Subject::all();
        return view('backend.pages.sms.homework.index', compact('homeworks', 'classes', 'sections', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date',
            'total_marks' => 'nullable|numeric|min:0',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:10240',
        ]);

        $homework = new Homework();
        $homework->title = $request->title;
        $homework->description = $request->description;
        $homework->class_id = $request->class_id;
        $homework->section_id = $request->section_id;
        $homework->subject_id = $request->subject_id;
        $homework->due_date = $request->due_date;
        $homework->total_marks = $request->total_marks ?? 100;
        $homework->status = $request->status ?? 'Active';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('backend/uploads/homework/'), $fileName);
            $homework->file_path = 'backend/uploads/homework/' . $fileName;
        }

        $homework->save();
        return redirect()->back()->with('success', 'Homework assigned successfully.');
    }

    public function show($id)
    {
        $homework = Homework::with(['academicClass', 'section', 'subject', 'submissions.student', 'submissions.grader'])->findOrFail($id);

        // Fetch all students enrolled in this class and section
        $students = \App\Models\Student::whereHas('enrollments', function ($q) use ($homework) {
            $q->where('academic_class_id', $homework->class_id)
              ->where('section_id', $homework->section_id);
        })->get();

        // Index existing submissions by student_id
        $submissionsByStudent = $homework->submissions->keyBy('student_id');

        return view('backend.pages.sms.homework.show', compact('homework', 'students', 'submissionsByStudent'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date',
            'total_marks' => 'nullable|numeric|min:0',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:10240',
        ]);

        $homework = Homework::findOrFail($id);
        $homework->title = $request->title;
        $homework->description = $request->description;
        $homework->class_id = $request->class_id;
        $homework->section_id = $request->section_id;
        $homework->subject_id = $request->subject_id;
        $homework->due_date = $request->due_date;
        $homework->total_marks = $request->total_marks ?? 100;
        $homework->status = $request->status ?? 'Active';

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($homework->file_path && file_exists(public_path($homework->file_path))) {
                @unlink(public_path($homework->file_path));
            }
            
            $file = $request->file('file');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('backend/uploads/homework/'), $fileName);
            $homework->file_path = 'backend/uploads/homework/' . $fileName;
        }

        $homework->save();
        return redirect()->back()->with('success', 'Homework updated successfully.');
    }

    public function destroy($id)
    {
        $homework = Homework::findOrFail($id);
        if ($homework->file_path && file_exists(public_path($homework->file_path))) {
            @unlink(public_path($homework->file_path));
        }
        $homework->delete();
        return redirect()->back()->with('success', 'Homework deleted successfully.');
    }

    public function gradeSubmission(Request $request, $submissionId)
    {
        $request->validate([
            'marks_obtained' => 'required|numeric|min:0',
            'feedback'       => 'nullable|string|max:1000',
        ]);

        $submission = \App\Models\HomeworkSubmission::findOrFail($submissionId);
        $submission->update([
            'marks_obtained' => $request->marks_obtained,
            'feedback'       => $request->feedback,
            'status'         => 'graded',
            'graded_by'      => auth()->id(),
            'graded_at'      => now(),
        ]);

        return redirect()->back()->with('success', 'Student submission graded successfully.');
    }
}
