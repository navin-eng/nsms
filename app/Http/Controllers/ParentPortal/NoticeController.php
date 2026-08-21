<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use Carbon\Carbon;

class NoticeController extends Controller
{
    public function index()
    {
        $guardian = \App\Models\Guardian::where('user_id', auth()->id())->first();
        $notices = Notice::where('is_school', true)
            ->forGuardian($guardian)
            ->latest()
            ->paginate(15);

        return view('parent.notices', compact('notices'));
    }
}
