<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('sort_order')->get();
        return view('backend.pages.sms.timetable.periods.index', compact('periods'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'start_time' => 'required',
            'end_time'   => 'required',
            'sort_order' => 'nullable|integer',
            'is_break'   => 'nullable|boolean',
        ]);

        $data['is_break']   = $request->boolean('is_break');
        $data['sort_order'] = $data['sort_order'] ?? (Period::max('sort_order') + 1);

        Period::create($data);
        return back()->with('success', 'Period created successfully.');
    }

    public function update(Request $request, Period $period)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'start_time' => 'required',
            'end_time'   => 'required',
            'sort_order' => 'nullable|integer',
            'is_break'   => 'nullable|boolean',
        ]);

        $data['is_break'] = $request->boolean('is_break');
        $period->update($data);
        return back()->with('success', 'Period updated successfully.');
    }

    public function show(Period $period)
    {
        return response()->json($period);
    }

}
