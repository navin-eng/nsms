<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Constraint\Count;

class CourseController extends Controller
{
    protected function rules($courseId = null)
    {
        $nameRule = 'required|min:2|max:80|unique:courses,name';

        if ($courseId) {
            $nameRule .= ',' . $courseId;
        }

        return [
            'name' => $nameRule,
            'duration' => 'required|string|max:100',
            'semester' => 'required|string|max:100',
            'requirement' => 'required|string|max:100',
            'starting_time' => 'nullable',
            'closing_time' => 'nullable',
            'description' => 'required|string|max:255',
            'fulldescription' => 'nullable|string',
            'image' => $courseId ? 'nullable|image|mimes:jpeg,png,jpg' : 'required|image|mimes:jpeg,png,jpg',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg',
        ];
    }

    public function index()
    {
        $course = Course::all();
        return view('backend.pages.course.table', compact('course'));
    }
    public function create()
    {
        return view('backend.pages.course.add');
    }
    public function store(Request $request)
    {
        $request->validate($this->rules());
        $course = new Course();
        $course->name = $request->name;
        $course->slug = Str::slug($request->name);
        $course->duration = $request->duration;
        $course->semester = $request->semester;
        $course->requirement = $request->requirement;
        $course->starting_time = $request->starting_time ? Carbon::parse($request->starting_time)->format('g:i A') : null;
        $course->closing_time = $request->closing_time ? Carbon::parse($request->closing_time)->format('g:i A') : null;
        $course->description = $request->description;
        $course->fulldescription = $request->fulldescription ?: null;
        $course->status = 1;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/courses/', $imageName);
            $course->image = 'backend/images/courses/' . $imageName;
        }
        if ($request->hasFile('gallery')) {
            $images = [];
            foreach ($request->file('gallery') as $img) {
                $extension = $img->getClientOriginalExtension();
                $imageName = Str::random(20) . time() . '.' . $extension;
                $img->move('backend/images/courses/', $imageName);
                $images[] = $imageName;
            }
            $course->gallery = json_encode($images);
        }
        $save = $course->save();
        if ($save == true) {

            Alert::success('Saved', 'course saved successfully');
            return back();
        } else {
            Alert::error('oops', 'Course couldnot saved');
            return back();
        }
    }
    public function edit(Course $course,$id)
    {
        $course = Course::find($id);
        if(is_null($course))
        {
            Alert::error('oops','Something went wrong');
        }
        else
        {
            return view('backend.pages.course.edit',compact('course'));
        }
    }
    public function status($id)
    {
        $course = Course::find($id);
        if (is_null($course)) {
            Alert::error('oops', 'We Couldnot find course');
        } else {
            if ($course->status == 1) {
                $course->status = null;
                $course->update();
                Alert::success('Updated', 'Status Deactivate');
                return back();
            } else {
                $course->status = 1;
                $course->update();
                Alert::success('Updated', 'Status Activate');
                return back();
            }
        }
    }
    public function update(Request $request, Course $course,$id)
    {
        $request->validate($this->rules($id));
        $course = Course::find($id);
        $course->name = $request->name;
        $course->slug = Str::slug($request->name);
        $course->duration = $request->duration;
        $course->semester = $request->semester;
        $course->requirement = $request->requirement;
        if($request->starting_time != null) {
            $course->starting_time = Carbon::parse($request->starting_time)->format('g:i A');
        }
        if($request->closing_time != null) {
            $course->closing_time = Carbon::parse($request->closing_time)->format('g:i A');
        }
        $course->description = $request->description;
        $course->fulldescription = $request->fulldescription ?: null;
        $course->status = 1;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/courses/', $imageName);
            $course->image = 'backend/images/courses/' . $imageName;
        }
        if ($request->hasFile('gallery')) {
            $images = [];
            foreach ($request->file('gallery') as $img) {
                $extension = $img->getClientOriginalExtension();
                $imageName = Str::random(20) . time() . '.' . $extension;
                $img->move('backend/images/courses/', $imageName);
                $images[] = $imageName;
            }
            $course->gallery = json_encode($images);
        }
        $save = $course->update();
        if ($save == true) {

            Alert::success('Saved', 'course update successfully');
            return redirect()->route('course.table');
        } else {
            Alert::error('oops', 'Course couldnot update');
            return redirect()->route('course.table');
        }
    }
    public function destroy(Course $course, $id)
    {
        $course = Course::find($id);
        $course->delete();
        Alert::success('Deleted', 'course deleted');
        return redirect()->route('course.table');
    }

    public function galleryDelete($id,$index)
    {
        $gallery = Course::find($id);
        foreach(json_decode($gallery->gallery) as $img)
        {
            $images[] = $img;
        }
        unset($images[$index]);
        $galleries = json_encode($images);
        $gallery->gallery = $galleries;
        $gallery->update();
        Alert::success('Success','Image Deleted');
        return back();
    }
}
