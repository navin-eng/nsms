<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;

use App\Models\AcademicClass;
use App\Models\Section;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with('academicClass')->orderBy('academic_class_id')->get();
        $classes = AcademicClass::orderBy('numeric_value')->get();
        return view('backend.pages.sms.sections.index', compact('sections', 'classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'capacity' => 'nullable|integer|min:1',
        ]);

        Section::create($data);
        Alert::success('Success', 'Section added successfully');
        return back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'capacity' => 'nullable|integer|min:1',
        ]);

        Section::findOrFail($id)->update($data);
        Alert::success('Success', 'Section updated successfully');
        return back();
    }

    public function destroy($id)
    {
        Section::findOrFail($id)->delete();
        Alert::success('Success', 'Section deleted successfully');
        return back();
    }
}
