<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Homework;

class HomeworkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::with(['enrollments'])->where('user_id', $user->id)->firstOrFail();
        $activeEnrollment = $student->enrollments()->latest()->first();

        $homeworks = collect();
        if ($activeEnrollment) {
            $homeworks = Homework::with(['subject', 'submissions' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
                ->where('class_id', $activeEnrollment->academic_class_id)
                ->where('section_id', $activeEnrollment->section_id)
                ->latest()
                ->get();
        }

        return view('student.homework', compact('student', 'homeworks', 'activeEnrollment'));
    }

    public function submit(Request $request, $id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $homework = Homework::findOrFail($id);

        $request->validate([
            'comments' => 'nullable|string|max:1000',
            'file'     => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:10240',
        ]);

        if (!$request->hasFile('file') && empty($request->comments)) {
            return redirect()->back()->with('error', 'Please attach your work file or provide written answer remarks.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = 'sub_' . $student->id . '_' . time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/homework_submissions/'), $fileName);
            $filePath = 'uploads/homework_submissions/' . $fileName;
        }

        $isLate = now()->isAfter(\Carbon\Carbon::parse($homework->due_date)->endOfDay());
        $status = $isLate ? 'late' : 'submitted';

        $submission = \App\Models\HomeworkSubmission::updateOrCreate(
            ['homework_id' => $homework->id, 'student_id' => $student->id],
            [
                'file_path'       => $filePath ?? \DB::raw('file_path'),
                'comments'        => $request->comments,
                'submission_date' => now(),
                'status'          => $status,
            ]
        );

        if ($filePath && $submission->wasRecentlyCreated === false && $submission->file_path !== $filePath) {
            $submission->file_path = $filePath;
            $submission->save();
        }

        return redirect()->back()->with('success', 'Homework submitted successfully!');
    }
}
