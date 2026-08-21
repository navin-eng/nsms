<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\AcademicClass;
use App\Models\Section;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::where('is_school', true)->latest()->paginate(20);
        return view('backend.pages.sms.notices.index', compact('notices'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['super-admin'])->get();
        $academicClasses = AcademicClass::all();
        $sections = Section::all();
        return view('backend.pages.sms.notices.create', compact('roles', 'academicClasses', 'sections'));
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
        $notice->show_in = 'p'; // Popup/modal inside app
        $notice->is_school = true;
        
        $notice->target_roles = $request->target_roles;
        $notice->target_classes = $request->target_classes;
        $notice->target_sections = $request->target_sections;
        $notice->status = $request->status;
        $notice->published_at = $request->status === 'published' ? now() : null;

        $save = $notice->save();
        
        if ($save) {
            // Trigger notifications if published
            if ($notice->status === 'published' && $request->has('notify_channels')) {
                \Illuminate\Support\Facades\Log::info("School Notice Published. Notifying via: " . implode(',', $request->notify_channels));
            }
            
            Alert::success('Saved', 'School notice saved successfully');
            return redirect()->route('sms.school-notices.index');
        } else {
            Alert::error('Oops', 'School notice could not be saved');
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $notice = Notice::where('is_school', true)->findOrFail($id);
        $roles = Role::whereNotIn('name', ['super-admin'])->get();
        $academicClasses = AcademicClass::all();
        $sections = Section::all();
        return view('backend.pages.sms.notices.edit', compact('notice', 'roles', 'academicClasses', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:2',
            'description' => 'required',
            'target_roles' => 'nullable|array',
            'target_classes' => 'nullable|array',
            'target_sections' => 'nullable|array',
            'status' => 'required|in:draft,published',
            'notify_channels' => 'nullable|array'
        ]);

        $notice = Notice::where('is_school', true)->findOrFail($id);
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
        $notice->target_roles = $request->target_roles;
        $notice->target_classes = $request->target_classes;
        $notice->target_sections = $request->target_sections;
        
        if ($request->status === 'published' && $notice->status !== 'published') {
            $notice->published_at = now();
        }
        $notice->status = $request->status;

        $save = $notice->update();
        
        if ($save) {
            if ($notice->status === 'published' && $request->has('notify_channels')) {
                \Illuminate\Support\Facades\Log::info("School Notice Updated. Notifying via: " . implode(',', $request->notify_channels));
            }
            
            Alert::success('Saved', 'School notice updated successfully');
            return redirect()->route('sms.school-notices.index');
        } else {
            Alert::error('Oops', 'School notice could not be updated');
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        $notice = Notice::where('is_school', true)->findOrFail($id);
        $notice->delete();
        Alert::success('Deleted', 'School notice deleted');
        return redirect()->route('sms.school-notices.index');
    }
}
