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
        $subjects = Subject::orderBy('name')->get();
        return view('backend.pages.sms.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects',
            'type' => 'required|in:theory,practical,both',
        ]);

        Subject::create($data);
        Alert::success('Success', 'Subject added successfully');
        return back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
            'type' => 'required|in:theory,practical,both',
        ]);

        Subject::findOrFail($id)->update($data);
        Alert::success('Success', 'Subject updated successfully');
        return back();
    }

    public function destroy($id)
    {
        Subject::findOrFail($id)->delete();
        Alert::success('Success', 'Subject deleted successfully');
        return back();
    }
}
