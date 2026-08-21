<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;

class NoticeController extends Controller
{
    public function index()
    {
        $student = \App\Models\Student::where('user_id', auth()->id())->first();
        $notices = Notice::where('is_school', true)
            ->forStudent($student)
            ->latest()
            ->paginate(10);

        return view('student.notices', compact('notices'));
    }

    public function show($id)
    {
        $notice = Notice::where('status', 'published')->findOrFail($id);
        return view('student.notice-show', compact('notice'));
    }
}
