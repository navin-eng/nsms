<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;



class NoticeController extends Controller
{
    public function index()
    {
        $notice = Notice::all();
        return view('backend.pages.notice.table', compact('notice'));
    }
    public function create()
    {
        return view('backend.pages.notice.add');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:2',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'description' => 'required',
        ]);
        $notice = new Notice();
        $notice->title = ucwords($request->title);
        $notice->slug = Str::slug($request->title);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/notices/', $imageName);
            $notice->image = 'backend/images/notices/' . $imageName;
        }
        $notice->description = $request->description;
        $notice->show_in = 'm';
        $save = $notice->save();
        if ($save == true) {

            Alert::success('Saved', 'notice saved successfully');
            return back();
        } else {
            Alert::error('oops', 'notice couldnot saved');
            return back();
        }
    }
    public function edit(notice $notice,$id)
    {
        $notice = Notice::find($id);
        if(is_null($notice))
        {
            Alert::error('oops','Something went wrong');
        }
        else
        {
            return view('backend.pages.notice.edit',compact('notice'));
        }
    }
    public function status($id)
    {
        $notice = Notice::find($id);
        if (is_null($notice)) {
            Alert::error('oops', 'We Couldnot find notice');
        } else {
            if ($notice->show_in == 'p') {
                $notice->show_in = 'm';
                $notice->update();
                Alert::success('Updated', 'marque activated');
                return back();
            } else {
                $notice->show_in = 'p';
                $notice->update();
                Alert::success('Updated', 'popup activated');
                return back();
            }
        }
    }
    public function update(Request $request, notice $notice,$id)
    {

        $request->validate([
            'title' => 'required|min:2',
            'description' => 'required',
        ]);
        $notice = Notice::find($id);
        $notice->title = ucwords($request->title);
        $notice->slug = Str::slug($request->title);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/notices/', $imageName);
            $notice->image = 'backend/images/notices/' . $imageName;
        }
        $notice->description = $request->description;
        $notice->show_in = 'm';
        $save = $notice->update();
        if ($save == true) {

            Alert::success('Saved', 'notice update successfully');
            return redirect()->route('notice.table');
        } else {
            Alert::error('oops', 'notice couldnot update');
            return redirect()->route('notice.table');
        }
    }
    public function destroy($id)
    {
        $notice = Notice::find($id);
        $notice->delete();
        Alert::success('Deleted', 'notice deleted');
        return redirect()->route('notice.table');
    }
}
