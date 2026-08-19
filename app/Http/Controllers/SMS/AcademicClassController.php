<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;

use App\Models\AcademicClass;
use App\Models\Stream;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AcademicClassController extends Controller
{
    public function index()
    {
        $classes = AcademicClass::with('stream')->orderBy('numeric_value', 'asc')->get();
        $streams = Stream::all();
        return view('backend.pages.sms.academic_classes.index', compact('classes', 'streams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'numeric_value' => 'required|integer|min:1',
            'stream_id' => 'nullable|exists:streams,id',
        ]);

        AcademicClass::create($data);
        Alert::success('Success', 'Class added successfully');
        return back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'numeric_value' => 'required|integer|min:1',
            'stream_id' => 'nullable|exists:streams,id',
        ]);

        AcademicClass::findOrFail($id)->update($data);
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
