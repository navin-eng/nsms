<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;

use App\Models\Subject;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('classes')->orderBy('order_level')->orderBy('name')->get();
        $classes = \App\Models\AcademicClass::orderBy('level')->get();
        return view('backend.pages.sms.subjects.index', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects',
            'type' => 'required|in:theory,practical,both',
            'order_level' => 'nullable|integer',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:academic_classes,id'
        ]);

        $subjectData = collect($data)->except(['classes'])->toArray();
        $subjectData['order_level'] = $subjectData['order_level'] ?? 0;

        $subject = Subject::create($subjectData);
        if ($request->has('classes')) {
            $subject->classes()->sync($request->classes);
        }

        Alert::success('Success', 'Subject added successfully');
        return back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
            'type' => 'required|in:theory,practical,both',
            'order_level' => 'nullable|integer',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:academic_classes,id'
        ]);

        $subjectData = collect($data)->except(['classes'])->toArray();
        $subjectData['order_level'] = $subjectData['order_level'] ?? 0;

        $subject = Subject::findOrFail($id);
        $subject->update($subjectData);
        
        if ($request->has('classes')) {
            $subject->classes()->sync($request->classes);
        } else {
            $subject->classes()->sync([]);
        }

        Alert::success('Success', 'Subject updated successfully');
        return back();
    }

    public function destroy($id)
    {
        Subject::findOrFail($id)->delete();
        Alert::success('Success', 'Subject deleted successfully');
        return back();
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'integer|min:0'
        ]);

        foreach ($request->orders as $id => $order) {
            Subject::where('id', $id)->update(['order_level' => $order]);
        }

        Alert::success('Success', 'Subject orders updated successfully');
        return back();
    }
}
