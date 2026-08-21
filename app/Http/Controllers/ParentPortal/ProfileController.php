<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class ProfileController extends Controller
{
    public function index()
    {
        $childId = session('active_child_id');
        $child = Student::with(['currentEnrollment.academicClass', 'currentEnrollment.section', 'guardian', 'documents'])->findOrFail($childId);
        
        return view('parent.profile', compact('child'));
    }
}
