<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EditorController extends Controller
{
    //

    public function index()
    {
        $editor  =  User::where('a_type', '=', 'E')->get();
        return view('backend.pages.editor.table', compact('editor'));
    }

    public function edit($id)
    {
        $editor = User::find($id);
        return view('backend.pages.editor.edit', compact('editor'));
    }
    public function update(Request $request, $id)
    {
        $editor = User::find($id);
        $editor->name = $request->name;
        $editor->email = $request->email;
        if ($request->filled('password')) {
            $editor->password = Hash::make($request->password);
        }
        if ($editor->a_type = 'A') {
            $editor->a_type = 'A';
        } else {
            $editor->a_type = 'E';
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . rand(0, 9999) . time() . '.' . $extension;
            $image->move('backend/admin/images/', $imageName);
            $editor->image = 'backend/admin/images/' . $imageName;
        }
        $editor->update();
        if ($editor->a_type = 'A') {
            $editor->a_type = 'A';
            session()->flash('success', 'updated successfully');
            return redirect('/admin/dashboard/profile');
        } else {
            $editor->a_type = 'E';
            session()->flash('success', 'updated successfully');
            return redirect('/admin/dashboard/editor/table');
        }
    }

    public function delete($id)
    {
        $editor = User::find($id);
        $editor->delete();
        session()->flash('success', 'deleted successfully');
        return redirect('/admin/dashboard/editor/table');
    }
}
