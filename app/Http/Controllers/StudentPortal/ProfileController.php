<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::with(['guardian', 'enrollments.academicYear', 'enrollments.academicClass', 'enrollments.section'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $activeEnrollment = $student->enrollments()->latest()->first();

        return view('student.profile', compact('student', 'activeEnrollment'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'theme' => 'required|string|in:default,barbie,ben10,spiderman,dark,scifi',
            'font' => 'required|string|in:Inter,Comic Sans MS,Courier New,Impact,Trebuchet MS',
            'avatar' => 'nullable|string|in:robot,ninja,astronaut,unicorn,dinosaur,superhero,alien,wizard',
        ]);

        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $student->theme = $request->theme;
        $student->font = $request->font;
        if ($request->has('avatar')) {
            $student->avatar = $request->avatar;
        }
        $student->save();

        return back()->with('success', 'Your customization settings have been saved! Enjoy your new look.');
    }
}
