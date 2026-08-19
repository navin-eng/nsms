<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;


class BannerController extends Controller
{
    public function index()
    {
        $banner = Banner::all();
        return view('backend.pages.banner.table', compact('banner'));
    }
    public function create()
    {
        return view('backend.pages.banner.add');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title1' => 'required|min:2',
            'title2' => 'required|min:2',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);
        $banner = new Banner();
        $banner->title1 = ucwords($request->title1);
        $banner->title2 = ucwords($request->title2);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/banners/', $imageName);
            $banner->image = 'backend/images/banners/' . $imageName;
        }
        $banner->status = 1;
        $save = $banner->save();
        if ($save == true) {

            Alert::success('Saved', 'banner saved successfully');
            return back();
        } else {
            Alert::error('oops', 'banner couldnot saved');
            return back();
        }
    }
    public function edit(banner $banner,$id)
    {
        $banner = Banner::find($id);
        if(is_null($banner))
        {
            Alert::error('oops','Something went wrong');
        }
        else
        {
            return view('backend.pages.banner.edit',compact('banner'));
        }
    }
    public function status($id)
    {
        $banner = Banner::find($id);
        if (is_null($banner)) {
            Alert::error('oops', 'We Couldnot find banner');
        } else {
            if ($banner->status == 0) {
                $banner->status = 1;
                $banner->update();
                Alert::success('Updated', 'status activated');
                return back();
            } else {
                $banner->status = 0;
                $banner->update();
                Alert::success('Updated', 'status deactivated');
                return back();
            }
        }
    }
    public function update(Request $request, banner $banner,$id)
    {

        $request->validate([
            'title1' => 'required|min:2',
            'title2' => 'required|min:2',
        ]);
        $banner = Banner::find($id);
        $banner->title1 = ucwords($request->title1);
        $banner->title2 = ucwords($request->title2);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/banners/', $imageName);
            $banner->image = 'backend/images/banners/' . $imageName;
        }
        $banner->status = 1;
        $save = $banner->update();
        if ($save == true) {

            Alert::success('Saved', 'banner update successfully');
            return redirect()->route('banner.table');
        } else {
            Alert::error('oops', 'banner couldnot update');
            return redirect()->route('banner.table');
        }
    }
    public function destroy($id)
    {
        $banner = Banner::find($id);
        $banner->delete();
        Alert::success('Deleted', 'banner deleted');
        return redirect()->route('banner.table');
    }
}
