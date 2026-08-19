<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $event = Event::all();
        return view('backend.pages.event.table', compact('event'));
    }
    public function create()
    {
        return view('backend.pages.event.add');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'event_type' => 'required|in:event,holiday,exam,test,cca_eca,result',
            'visit_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'result_link' => 'nullable|string|max:255',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp',
        ]);
        $event = new Event();
        $event->name = $request->name;
        $event->slug = Str::slug($request->name);
        $event->event_type = $request->event_type;
        $event->visit_date = $request->visit_date;
        $event->venue = $request->venue;
        $event->result_link = $request->result_link;
        $event->description = $request->description;
        $event->status = 1;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/events/', $imageName);
            $event->image = 'backend/images/events/' . $imageName;
        }

        if ($request->hasFile('gallery')) {
            $event->gallery = $this->storeEventGalleryImages($request->file('gallery'));
        }

        $save = $event->save();
        if ($save == true) {

            Alert::success('Saved', 'event saved successfully');
            return back();
        } else {
            Alert::error('oops', 'Course couldnot saved');
            return back();
        }
    }
    public function edit($id)
    {
        $event = Event::find($id);
        if(is_null($event))
        {
            Alert::error('oops','Something went wrong');
        }
        else
        {
            return view('backend.pages.event.edit',compact('event'));
        }
    }
    public function status($id)
    {
        $event = Event::find($id);
        if (is_null($event)) {
            Alert::error('oops', 'We Couldnot find event');
        } else {
            if ($event->status == 1) {
                $event->status = null;
                $event->update();
                Alert::success('Updated', 'Status Deactivate');
                return back();
            } else {
                $event->status = 1;
                $event->update();
                Alert::success('Updated', 'Status Activate');
                return back();
            }
        }
    }
    public function update(Request $request,$id)
    {

        $request->validate([
            'name' => 'required',
            'event_type' => 'required|in:event,holiday,exam,test,cca_eca,result',
            'visit_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'result_link' => 'nullable|string|max:255',
            'description' => 'required',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp',
        ]);
        $event = Event::findOrFail($id);
        $event->name = $request->name;
        $event->slug = Str::slug($request->name);
        $event->event_type = $request->event_type;
        $event->visit_date = $request->visit_date;
        $event->venue = $request->venue;
        $event->result_link = $request->result_link;
        $event->description = $request->description;
        $event->status = 1;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/events/', $imageName);
            $event->image = 'backend/images/events/' . $imageName;
        }

        if ($request->hasFile('gallery')) {
            $existingGallery = $event->gallery ?: [];
            $event->gallery = array_merge($existingGallery, $this->storeEventGalleryImages($request->file('gallery')));
        }

        $save = $event->update();
        if ($save == true) {

            Alert::success('Saved', 'event update successfully');
            return redirect()->route('event.table');
        } else {
            Alert::error('oops', 'Course couldnot update');
            return redirect()->route('event.table');
        }
    }
    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();
        Alert::success('Deleted', 'event deleted');
        return redirect()->route('event.table');
    }

    public function galleryDelete($id,$index)
    {
        $gallery = Event::findOrFail($id);
        $images = $gallery->gallery ?: [];

        if (isset($images[$index])) {
            $imagePath = public_path($images[$index]);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        unset($images[$index]);
        $gallery->gallery = array_values($images);
        $gallery->update();
        Alert::success('Success','Image Deleted');
        return back();
    }

    private function storeEventGalleryImages(array $galleryFiles): array
    {
        $images = [];

        foreach ($galleryFiles as $img) {
            $extension = $img->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $img->move('backend/images/events/', $imageName);
            $images[] = 'backend/images/events/' . $imageName;
        }

        return $images;
    }
}
