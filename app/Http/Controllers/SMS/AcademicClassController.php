<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;

use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\Stream;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AcademicClassController extends Controller
{
    public function index()
    {
        $classes = AcademicClass::with(['stream', 'sections'])->orderBy('numeric_value', 'asc')->get();
        $streams = Stream::all();
        $allSections = Section::orderBy('name')->get();
        return view('backend.pages.sms.academic_classes.index', compact('classes', 'streams', 'allSections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'numeric_value' => 'required|integer|min:1',
            'stream_id' => 'nullable|exists:streams,id',
            'section_ids' => 'nullable|array',
            'section_ids.*' => 'exists:sections,id',
        ]);

        $class = AcademicClass::create([
            'name' => $data['name'],
            'numeric_value' => $data['numeric_value'],
            'stream_id' => $data['stream_id'] ?? null,
        ]);

        // Attach selected sections via pivot (many-to-many)
        if (!empty($data['section_ids'])) {
            $class->sections()->sync($data['section_ids']);
        }

        Alert::success('Success', 'Class added successfully');
        return back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'numeric_value' => 'required|integer|min:1',
            'stream_id' => 'nullable|exists:streams,id',
            'section_ids' => 'nullable|array',
            'section_ids.*' => 'exists:sections,id',
        ]);

        $class = AcademicClass::findOrFail($id);
        $class->update([
            'name' => $data['name'],
            'numeric_value' => $data['numeric_value'],
            'stream_id' => $data['stream_id'] ?? null,
        ]);

        // Sync sections via many-to-many pivot
        $class->sections()->sync($data['section_ids'] ?? []);

        Alert::success('Success', 'Class updated successfully');
        return back();
    }

    public function destroy($id)
    {
        AcademicClass::findOrFail($id)->delete();
        Alert::success('Success', 'Class deleted successfully');
        return back();
    }
}
