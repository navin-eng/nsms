<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;

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
        $roles = \Spatie\Permission\Models\Role::whereNotIn('name', ['super-admin'])->get();
        $academicClasses = \App\Models\AcademicClass::all();
        $sections = \App\Models\Section::all();
        return view('backend.pages.notice.add', compact('roles', 'academicClasses', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'description' => 'required',
            'target_roles' => 'nullable|array',
            'target_classes' => 'nullable|array',
            'target_sections' => 'nullable|array',
            'target_users' => 'nullable|array',
            'status' => 'required|in:draft,published',
            'notify_channels' => 'nullable|array'
        ]);

        $notice = new Notice();
        $notice->title = ucwords($request->title);
        $notice->slug = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/notices/', $imageName);
            $notice->image = 'backend/images/notices/' . $imageName;
        } else {
            $notice->image = 'default.png';
        }

        $notice->description = $request->description;
        $notice->show_in = $request->show_in ?? 'p'; // popup by default

        $notice->target_roles = $request->target_roles;
        $notice->target_classes = $request->target_classes;
        $notice->target_sections = $request->target_sections;
        $notice->target_users = $request->target_users;
        $notice->status = $request->status;
        $notice->published_at = $request->status === 'published' ? now() : null;

        $save = $notice->save();

        if ($save) {
            // Trigger Communication Service if channels are selected and status is published
            if ($notice->status === 'published' && $request->has('notify_channels')) {
                // Here we would extract the targeted users and dispatch the notifications
                // For now we just log it as a placeholder until the user resolution logic is fully built out
                \Illuminate\Support\Facades\Log::info("Notice Published. Need to notify via: " . implode(',', $request->notify_channels));
            }

            Alert::success('Saved', 'notice saved successfully');
            return redirect()->route('notice.table');
        } else {
            Alert::error('oops', 'notice could not be saved');
            return back();
        }
    }
    public function edit($id)
    {
        $notice = Notice::find($id);
        if (is_null($notice)) {
            Alert::error('oops', 'Something went wrong');
            return back();
        } else {
            $roles = \Spatie\Permission\Models\Role::whereNotIn('name', ['super-admin'])->get();
            $academicClasses = \App\Models\AcademicClass::all();
            $sections = \App\Models\Section::all();
            return view('backend.pages.notice.edit', compact('notice', 'roles', 'academicClasses', 'sections'));
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:2',
            'description' => 'required',
            'target_roles' => 'nullable|array',
            'target_classes' => 'nullable|array',
            'target_sections' => 'nullable|array',
            'target_users' => 'nullable|array',
            'status' => 'required|in:draft,published',
            'notify_channels' => 'nullable|array'
        ]);
        $notice = Notice::find($id);
        $notice->title = ucwords($request->title);
        $notice->slug = Str::slug($request->title) . '-' . time();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/notices/', $imageName);
            $notice->image = 'backend/images/notices/' . $imageName;
        }
        $notice->description = $request->description;
        $notice->show_in = $request->show_in ?? 'p';

        $notice->target_roles = $request->target_roles;
        $notice->target_classes = $request->target_classes;
        $notice->target_sections = $request->target_sections;
        $notice->target_users = $request->target_users;

        // Only update published_at if it wasn't already published
        if ($request->status === 'published' && $notice->status !== 'published') {
            $notice->published_at = now();
        }
        $notice->status = $request->status;

        $save = $notice->update();
        if ($save) {
            // Trigger Communication Service if channels are selected and status is published
            if ($notice->status === 'published' && $request->has('notify_channels')) {
                \Illuminate\Support\Facades\Log::info("Notice Updated & Published. Need to notify via: " . implode(',', $request->notify_channels));
            }
            Alert::success('Saved', 'notice update successfully');
            return redirect()->route('notice.table');
        } else {
            Alert::error('oops', 'notice could not update');
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
