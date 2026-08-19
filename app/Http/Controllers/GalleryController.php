<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::all();
        return view('backend.pages.gallery.table', compact('gallery'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'gallery' => 'required|array|min:1',
            'gallery.*' => 'required|image|mimes:jpeg,png,jpg,webp',
        ]);
        $gallery = new Gallery();
        $images = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $extension = $img->getClientOriginalExtension();
                $imageName = Str::random(20) . time() . '.' . $extension;
                $img->move('backend/images/gallery/', $imageName);
                $images[] = $imageName;
            }
            $gallery->gallery = json_encode($images);
        }
        $save = $gallery->save();
        if ($save == true) {

            Alert::success('Saved', 'gallery saved successfully');
            return back();
        } else {
            Alert::error('oops', 'gallery couldnot saved');
            return back();
        }
    }
    public function galleryDelete($id,$index)
    {
        $gallery = Gallery::findOrFail($id);
        $images = json_decode($gallery->gallery, true) ?: [];

        if (isset($images[$index])) {
            $imagePath = public_path('backend/images/gallery/' . $images[$index]);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        unset($images[$index]);
        $gallery->gallery = json_encode(array_values($images));
        $gallery->update();
        Alert::success('Success','Image Deleted');
        return back();
    }
}
