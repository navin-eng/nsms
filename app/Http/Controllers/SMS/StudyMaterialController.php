<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\StudyMaterial;
use App\Models\AcademicClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudyMaterialController extends Controller
{
    public function index()
    {
        $materials = StudyMaterial::with(['academicClass', 'subject'])->latest()->get();
        $classes = AcademicClass::all();
        $subjects = Subject::all();
        return view('backend.pages.sms.materials.index', compact('materials', 'classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:academic_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:10240',
        ]);

        $material = new StudyMaterial();
        $material->title = $request->title;
        $material->description = $request->description;
        $material->class_id = $request->class_id;
        $material->subject_id = $request->subject_id;
        $material->status = $request->status ?? 'Active';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move('backend/uploads/materials/', $fileName);
            $material->file_path = 'backend/uploads/materials/' . $fileName;
        }

        $material->save();
        return redirect()->back()->with('success', 'Study material uploaded successfully.');
    }

    public function show($id)
    {
        $material = StudyMaterial::with(['academicClass', 'subject'])->findOrFail($id);
        return view('backend.pages.sms.materials.show', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:academic_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:10240',
        ]);

        $material = StudyMaterial::findOrFail($id);
        $material->title = $request->title;
        $material->description = $request->description;
        $material->class_id = $request->class_id;
        $material->subject_id = $request->subject_id;
        $material->status = $request->status ?? 'Active';

        if ($request->hasFile('file')) {
            if ($material->file_path && file_exists(public_path($material->file_path))) {
                unlink(public_path($material->file_path));
            }
            $file = $request->file('file');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move('backend/uploads/materials/', $fileName);
            $material->file_path = 'backend/uploads/materials/' . $fileName;
        }

        $material->save();
        return redirect()->back()->with('success', 'Study material updated successfully.');
    }

    public function destroy($id)
    {
        $material = StudyMaterial::findOrFail($id);
        if ($material->file_path && file_exists(public_path($material->file_path))) {
            unlink(public_path($material->file_path));
        }
        $material->delete();
        return redirect()->back()->with('success', 'Study material deleted successfully.');
    }
}
