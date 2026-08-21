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
        $sections = Section::with('academicClasses')->orderBy('name')->get();
        $classes = AcademicClass::orderBy('numeric_value')->get();
        return view('backend.pages.sms.sections.index', compact('sections', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:1000',
            'academic_class_id' => 'nullable|exists:academic_classes,id',
            'capacity' => 'nullable|integer|min:1',
        ]);

        // Split by comma — allow bulk creation like "A, B, C" or "Neptune, Pluto"
        $names = array_filter(array_map('trim', explode(',', $request->name)));

        $created = 0;
        foreach ($names as $name) {
            if ($name === '') continue;
            Section::create([
                'name'              => $name,
                'academic_class_id' => $request->academic_class_id ?: null,
                'capacity'          => $request->capacity ?: null,
            ]);
            $created++;
        }

        $label = $created === 1 ? 'Section' : 'Sections';
        Alert::success('Success', "{$created} {$label} added successfully");
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
