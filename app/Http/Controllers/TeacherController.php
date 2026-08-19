<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;


class TeacherController extends Controller
{
    public function index()
    {
        $teacher = Teacher::all();
        return view('backend.pages.teacher.table', compact('teacher'));
    }
    public function create()
    {
        return view('backend.pages.teacher.add');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:30',
            'role' => 'required',
            'staff_type' => 'required|in:teaching,non_teaching,administrative',
            'facebook_link' => 'nullable',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);
        $teacher = new Teacher();
        $teacher->name = $request->name;
        $teacher->role = $request->role;
        $teacher->staff_type = $request->staff_type;
        $teacher->facebook_link = $request->facebook_link;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/teachers/', $imageName);
            $teacher->image = 'backend/images/teachers/' . $imageName;
        }
        $save = $teacher->save();
        if ($save == true) {

            Alert::success('Saved', 'teacher saved successfully');
            return back();
        } else {
            Alert::error('oops', 'teacher couldnot saved');
            return back();
        }
    }
    public function edit(teacher $teacher,$id)
    {
        $teacher = Teacher::find($id);
        if(is_null($teacher))
        {
            Alert::error('oops','Something went wrong');
        }
        else
        {
            return view('backend.pages.teacher.edit',compact('teacher'));
        }
    }
    public function status($id)
    {
        $teacher = Teacher::find($id);
        if (is_null($teacher)) {
            Alert::error('oops', 'We Couldnot find teacher');
        } else {
            if ($teacher->status == 1) {
                $teacher->status = null;
                $teacher->update();
                Alert::success('Updated', 'Status Deactivate');
                return back();
            } else {
                $teacher->status = 1;
                $teacher->update();
                Alert::success('Updated', 'Status Activate');
                return back();
            }
        }
    }
    public function update(Request $request, teacher $teacher,$id)
    {

        $request->validate([
            'name' => 'required|min:2|max:30',
            'role' => 'required',
            'staff_type' => 'required|in:teaching,non_teaching,administrative',
            'facebook_link' => 'nullable',
        ]);
        $teacher = Teacher::find($id);
        $teacher->name = $request->name;
        $teacher->role = $request->role;
        $teacher->staff_type = $request->staff_type;
        $teacher->facebook_link = $request->facebook_link;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/teachers/', $imageName);
            $teacher->image = 'backend/images/teachers/' . $imageName;
        }
        $save = $teacher->update();
        if ($save == true) {

            Alert::success('Saved', 'teacher update successfully');
            return redirect()->route('teacher.table');
        } else {
            Alert::error('oops', 'teacher couldnot update');
            return redirect()->route('teacher.table');
        }
    }
    public function destroy(teacher $teacher, $id)
    {
        $teacher = Teacher::find($id);
        $teacher->delete();
        Alert::success('Deleted', 'teacher deleted');
        return redirect()->route('teacher.table');
    }
}
