<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = \App\Models\Exam::with('academicYear')->latest()->paginate(15);
        return view('backend.pages.sms.exams.index', compact('exams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $years = \App\Models\AcademicYear::orderByDesc('start_date')->get();
        return view('backend.pages.sms.exams.create', compact('years'));
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
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'title' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:Upcoming,Ongoing,Completed',
            'description' => 'nullable|string',
        ]);

        \App\Models\Exam::create($request->all());
        return redirect()->route('sms.exams.index')->with('success', 'Exam created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exam = \App\Models\Exam::findOrFail($id);
        return view('backend.pages.sms.exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam = \App\Models\Exam::findOrFail($id);
        $years = \App\Models\AcademicYear::orderByDesc('start_date')->get();
        return view('backend.pages.sms.exams.edit', compact('exam', 'years'));
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
        $request->validate([
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'title' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:Upcoming,Ongoing,Completed',
            'description' => 'nullable|string',
        ]);

        $exam = \App\Models\Exam::findOrFail($id);
        $exam->update($request->all());
        return redirect()->route('sms.exams.index')->with('success', 'Exam updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\Models\Exam::findOrFail($id)->delete();
        return redirect()->route('sms.exams.index')->with('success', 'Exam deleted successfully.');
    }
}
