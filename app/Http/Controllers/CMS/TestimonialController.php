<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;


class TestimonialController extends Controller
{
    public function index()
    {
        $testimonial = Testimonial::all();
        return view('backend.pages.testimonial.table', compact('testimonial'));
    }
    public function create()
    {
        return view('backend.pages.testimonial.add');
    }
    public function store(Request $request)
    {
        if ($request->has('testimonials')) {
            $request->validate([
                'testimonials' => 'required|array|min:1',
                'testimonials.*.name' => 'required|string|min:2|max:30',
                'testimonials.*.role' => 'required|string|max:255',
                'testimonials.*.description' => 'required|string',
                'testimonials.*.image' => 'required|image|mimes:jpeg,png,jpg',
            ]);

            foreach ($request->input('testimonials', []) as $index => $item) {
                $testimonial = new Testimonial();
                $testimonial->name = $item['name'];
                $testimonial->role = $item['role'];
                $testimonial->description = $item['description'];

                if ($request->hasFile("testimonials.$index.image")) {
                    $testimonial->image = $this->uploadTestimonialImage($request->file("testimonials.$index.image"));
                }

                $testimonial->save();
            }

            Alert::success('Saved', 'Testimonials saved successfully');
            return back();
        }

        $request->validate([
            'name' => 'required|min:2|max:30',
            'description' => 'required',
            'role' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);
        $testimonial = new testimonial();
        $testimonial->name = $request->name;
        $testimonial->role = $request->role;
        $testimonial->description = $request->description;
        if ($request->hasFile('image')) {
            $testimonial->image = $this->uploadTestimonialImage($request->file('image'));
        }
        $save = $testimonial->save();
        if ($save == true) {

            Alert::success('Saved', 'testimonial saved successfully');
            return back();
        } else {
            Alert::error('oops', 'testimonial couldnot saved');
            return back();
        }
    }
    public function edit(testimonial $testimonial,$id)
    {
        $testimonial = Testimonial::find($id);
        if(is_null($testimonial))
        {
            Alert::error('oops','Something went wrong');
        }
        else
        {
            return view('backend.pages.testimonial.edit',compact('testimonial'));
        }
    }
    public function status($id)
    {
        $testimonial = Testimonial::find($id);
        if (is_null($testimonial)) {
            Alert::error('oops', 'We Couldnot find testimonial');
        } else {
            if ($testimonial->status == 1) {
                $testimonial->status = null;
                $testimonial->update();
                Alert::success('Updated', 'Status Deactivate');
                return back();
            } else {
                $testimonial->status = 1;
                $testimonial->update();
                Alert::success('Updated', 'Status Activate');
                return back();
            }
        }
    }
    public function update(Request $request, testimonial $testimonial,$id)
    {

        $request->validate([
            'name' => 'required|min:2|max:30',
            'description' => 'required',
            'role' => 'required',
        ]);
        $testimonial = Testimonial::find($id);
        $testimonial->name = $request->name;
        $testimonial->role = $request->role;
        $testimonial->description = $request->description;
        if ($request->hasFile('image')) {
            $testimonial->image = $this->uploadTestimonialImage($request->file('image'));
        }
        $save = $testimonial->update();
        if ($save == true) {

            Alert::success('Saved', 'testimonial update successfully');
            return redirect()->route('testimonial.table');
        } else {
            Alert::error('oops', 'testimonial couldnot update');
            return redirect()->route('testimonial.table');
        }
    }
    public function destroy(testimonial $testimonial, $id)
    {
        $testimonial = Testimonial::find($id);
        $testimonial->delete();
        Alert::success('Deleted', 'testimonial deleted');
        return redirect()->route('testimonial.table');
    }

    private function uploadTestimonialImage($image)
    {
        $extension = $image->getClientOriginalExtension();
        $imageName = Str::random(20) . time() . '.' . $extension;
        $image->move('backend/images/testimonials/', $imageName);

        return 'backend/images/testimonials/' . $imageName;
    }
}
