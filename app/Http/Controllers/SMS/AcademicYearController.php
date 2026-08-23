<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;

use App\Models\AcademicYear;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Pratiksh\Nepalidate\Services\EnglishDate;

class AcademicYearController extends Controller
{
    public function index()
    {
        $years = AcademicYear::orderBy('start_date', 'desc')->get();
        $calendarSystem = app(\App\Services\CalendarService::class)->system();
        return view('backend.pages.sms.academic_years.index', compact('years', 'calendarSystem'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $calendarService = app(\App\Services\CalendarService::class);
        
        if ($calendarService->system() === 'BS' && !empty($input['start_date']) && !empty($input['end_date'])) {
            $input['start_date'] = $calendarService->toDbDate($input['start_date'])->toDateString();
            $input['end_date'] = $calendarService->toDbDate($input['end_date'])->toDateString();
            $request->merge($input);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if (AcademicYear::count() === 0) {
            $data['is_active'] = true;
        } else {
            $data['is_active'] = false;
        }

        AcademicYear::create($data);
        Alert::success('Success', 'Academic Year added successfully');
        return back();
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $calendarService = app(\App\Services\CalendarService::class);
        
        if ($calendarService->system() === 'BS' && !empty($input['start_date']) && !empty($input['end_date'])) {
            $input['start_date'] = $calendarService->toDbDate($input['start_date'])->toDateString();
            $input['end_date'] = $calendarService->toDbDate($input['end_date'])->toDateString();
            $request->merge($input);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        AcademicYear::findOrFail($id)->update($data);
        Alert::success('Success', 'Academic Year updated successfully');
        return back();
    }

    public function destroy($id)
    {
        $year = AcademicYear::findOrFail($id);
        if ($year->is_active) {
            Alert::error('Error', 'Cannot delete the active academic year.');
            return back();
        }
        $year->delete();
        Alert::success('Success', 'Academic Year deleted successfully');
        return back();
    }

    public function makeActive($id)
    {
        $targetYear = AcademicYear::findOrFail($id);
        
        if ($targetYear->is_active) {
            $targetYear->update(['is_active' => false]);
            Alert::success('Success', 'Academic Year marked as inactive');
        } else {
            AcademicYear::query()->update(['is_active' => false]);
            $targetYear->update(['is_active' => true]);
            Alert::success('Success', 'Active Academic Year changed');
        }
        
        return back();
    }
}
