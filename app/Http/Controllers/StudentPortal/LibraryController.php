<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use App\Models\LibraryIssue;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student; // Assuming auth()->user()->student resolves the currently logged in student.
        
        $issues = LibraryIssue::with(['bookCopy.book.category'])
            ->where('borrower_type', 'App\Models\Student')
            ->where('borrower_id', $student->id)
            ->latest('issue_date')
            ->get();
            
        $activeIssues = $issues->whereIn('status', ['issued', 'overdue']);
        $pastIssues = $issues->where('status', 'returned');

        return view('student.library.index', compact('activeIssues', 'pastIssues'));
    }
}
