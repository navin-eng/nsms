<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $exams = Exam::where('status', 1)->orderBy('created_at', 'desc')->get();
        return view('frontend.pages.results', compact('exams'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'symbol_number' => 'required|string',
        ]);

        $exam = Exam::findOrFail($request->exam_id);
        $result = ExamResult::where('exam_id', $exam->id)
            ->where('symbol_number', $request->symbol_number)
            ->first();

        $exams = Exam::where('status', 1)->orderBy('created_at', 'desc')->get();

        if (!$result) {
            return back()->with('error', 'No result found for the provided Symbol Number in this exam.');
        }

        return view('frontend.pages.results', compact('exams', 'exam', 'result'));
    }
}
