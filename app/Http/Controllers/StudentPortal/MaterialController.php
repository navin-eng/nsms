<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\StudyMaterial;

class MaterialController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::with(['enrollments'])->where('user_id', $user->id)->firstOrFail();
        $activeEnrollment = $student->enrollments()->latest()->first();

        $materials = collect();
        if ($activeEnrollment) {
            $materials = StudyMaterial::with(['subject'])
                ->where('class_id', $activeEnrollment->academic_class_id)
                ->latest()
                ->get();
        }

        return view('student.materials', compact('student', 'materials', 'activeEnrollment'));
    }
}
