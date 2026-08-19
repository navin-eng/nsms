<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;

use App\Models\CollegeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class CollegeMessageController extends Controller
{
    public function index()
    {
        $messages = CollegeMessage::orderBy('order')->get();
        return view('backend.pages.college_message.table', compact('messages'));
    }

    public function create()
    {
        return view('backend.pages.college_message.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|min:2',
            'designation' => 'required',
            'message'     => 'required',
        ]);

        $msg              = new CollegeMessage();
        $msg->name        = ucwords($request->name);
        $msg->designation = $request->designation;
        $msg->message     = $request->message;
        $msg->order       = $request->order ?? 0;
        $msg->status      = 1;

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = Str::random(20) . time() . '.' . $image->getClientOriginalExtension();
            $image->move('backend/images/messages/', $imageName);
            $msg->image = 'backend/images/messages/' . $imageName;
        }

        $saved = $msg->save();

        if ($saved) {
            Alert::success('Saved', 'Message saved successfully');
            return redirect()->route('college_message.table');
        }

        Alert::error('Oops', 'Message could not be saved');
        return back();
    }

    public function edit($id)
    {
        $msg = CollegeMessage::findOrFail($id);
        return view('backend.pages.college_message.edit', compact('msg'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|min:2',
            'designation' => 'required',
            'message'     => 'required',
        ]);

        $msg              = CollegeMessage::findOrFail($id);
        $msg->name        = ucwords($request->name);
        $msg->designation = $request->designation;
        $msg->message     = $request->message;
        $msg->order       = $request->order ?? 0;

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = Str::random(20) . time() . '.' . $image->getClientOriginalExtension();
            $image->move('backend/images/messages/', $imageName);
            $msg->image = 'backend/images/messages/' . $imageName;
        }

        $saved = $msg->update();

        if ($saved) {
            Alert::success('Updated', 'Message updated successfully');
            return redirect()->route('college_message.table');
        }

        Alert::error('Oops', 'Message could not be updated');
        return back();
    }

    public function status($id)
    {
        $msg         = CollegeMessage::findOrFail($id);
        $msg->status = $msg->status == 1 ? 0 : 1;
        $msg->update();
        Alert::success('Updated', 'Status changed');
        return back();
    }

    public function destroy($id)
    {
        CollegeMessage::findOrFail($id)->delete();
        Alert::success('Deleted', 'Message deleted successfully');
        return redirect()->route('college_message.table');
    }
}
