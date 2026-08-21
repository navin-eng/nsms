<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\LibraryBookCopy;
use App\Models\LibraryIssue;
use App\Models\Student;
use App\Models\Staff;
use App\Models\LibrarySetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class LibraryIssueController extends Controller
{
    public function index(Request $request)
    {
        $query = LibraryIssue::with(['bookCopy.book', 'borrower'])->latest();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('bookCopy', function($q) use ($search) {
                $q->where('barcode', 'like', "%{$search}%")
                  ->orWhereHas('book', function($bq) use ($search) {
                      $bq->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        $issues = $query->paginate(15);
        return view('backend.pages.sms.library.issues.index', compact('issues'));
    }

    public function create()
    {
        $settings = LibrarySetting::firstOrCreate([], [
            'max_borrow_days_student' => 7,
            'max_borrow_days_staff' => 14,
            'fine_per_day' => 5.00
        ]);
        
        return view('backend.pages.sms.library.issues.create', compact('settings'));
    }

    public function getBorrower(Request $request)
    {
        $type = $request->type;
        $id = $request->id; // ID could be admission_no, employee_id, or DB ID

        if ($type === 'App\Models\Student') {
            $borrower = Student::with(['currentEnrollment.academicClass'])->where('admission_no', $id)->orWhere('id', $id)->first();
            if($borrower) {
                return response()->json([
                    'success' => true,
                    'name' => $borrower->full_name,
                    'identifier' => $borrower->admission_no,
                    'info' => 'Class: ' . ($borrower->currentEnrollment?->academicClass?->name ?? 'N/A')
                ]);
            }
        } else {
            $borrower = Staff::with('department')->where('employee_id', $id)->orWhere('id', $id)->first();
            if($borrower) {
                return response()->json([
                    'success' => true,
                    'name' => $borrower->full_name,
                    'identifier' => $borrower->employee_id,
                    'info' => 'Department: ' . ($borrower->department?->name ?? 'N/A')
                ]);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'Borrower not found']);
    }

    public function getBorrowersList(Request $request)
    {
        $type = $request->type;
        $borrowers = [];

        if ($type === 'App\Models\Student') {
            $students = Student::select('id', 'first_name', 'last_name', 'admission_no')->get();
            foreach ($students as $student) {
                $borrowers[] = [
                    'id' => $student->admission_no,
                    'text' => $student->full_name . ' (' . $student->admission_no . ')'
                ];
            }
        } else {
            $staff = Staff::select('id', 'first_name', 'last_name', 'employee_id')->get();
            foreach ($staff as $st) {
                $borrowers[] = [
                    'id' => $st->employee_id,
                    'text' => $st->full_name . ' (' . $st->employee_id . ')'
                ];
            }
        }

        return response()->json(['success' => true, 'borrowers' => $borrowers]);
    }

    public function getBook(Request $request)
    {
        $barcode = $request->barcode;
        $copy = LibraryBookCopy::with('book')->where('barcode', $barcode)->first();
        
        if (!$copy) {
            return response()->json(['success' => false, 'message' => 'Barcode not found']);
        }
        
        if ($copy->status !== 'available') {
            return response()->json(['success' => false, 'message' => 'This copy is currently ' . $copy->status]);
        }
        
        return response()->json([
            'success' => true,
            'copy_id' => $copy->id,
            'title' => $copy->book->title,
            'author' => $copy->book->author,
            'isbn' => $copy->book->isbn,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrower_type' => 'required|string',
            'borrower_id' => 'required|string', // using the actual string id submitted from frontend
            'barcode' => 'required|string',
            'due_date' => 'required|date'
        ]);

        // Resolve Borrower
        if ($request->borrower_type === 'App\Models\Student') {
            $borrower = Student::where('admission_no', $request->borrower_id)->orWhere('id', $request->borrower_id)->firstOrFail();
        } else {
            $borrower = Staff::where('employee_id', $request->borrower_id)->orWhere('id', $request->borrower_id)->firstOrFail();
        }

        // Resolve Book Copy
        $copy = LibraryBookCopy::with('book')->where('barcode', $request->barcode)->firstOrFail();

        if ($copy->status !== 'available') {
            Alert::error('Error', 'Book is not available for issue.');
            return back();
        }

        // Create Issue
        LibraryIssue::create([
            'library_book_copy_id' => $copy->id,
            'borrower_type' => $request->borrower_type,
            'borrower_id' => $borrower->id,
            'issue_date' => now()->format('Y-m-d'),
            'due_date' => $request->due_date,
            'status' => 'issued',
            'issued_by' => auth()->id()
        ]);

        // Update Copy Status
        $copy->update(['status' => 'issued']);
        
        // Update Book Available Count
        $copy->book->decrement('available_copies');

        Alert::success('Success', 'Book issued successfully to ' . $borrower->full_name);
        return redirect()->route('sms.library.issues.index');
    }

    public function returnBook(Request $request, $id)
    {
        $issue = LibraryIssue::with(['bookCopy.book'])->findOrFail($id);
        
        if ($issue->status === 'returned') {
            Alert::info('Info', 'Book is already returned.');
            return back();
        }

        $fineAmount = $request->input('fine_amount', 0);
        $fineStatus = $fineAmount > 0 ? $request->input('fine_status', 'paid') : null;
        $condition = $request->input('condition', 'good');

        $issue->update([
            'return_date' => now()->format('Y-m-d'),
            'fine_amount' => $fineAmount,
            'fine_status' => $fineStatus,
            'status' => 'returned'
        ]);

        // Update Copy Status and Condition
        $newCopyStatus = in_array($condition, ['lost', 'damaged']) ? $condition : 'available';
        
        $issue->bookCopy->update([
            'status' => $newCopyStatus,
            'condition' => $condition
        ]);
        
        if ($newCopyStatus === 'available') {
            $issue->bookCopy->book->increment('available_copies');
        }

        Alert::success('Success', 'Book returned successfully.');
        return back();
    }

    public function borrowerHistory($type, $id)
    {
        $modelClass = $type === 'student' ? \App\Models\Student::class : \App\Models\Staff::class;
        $borrower = $modelClass::findOrFail($id);

        $issues = \App\Models\LibraryIssue::with(['bookCopy.book'])
            ->where('borrower_type', $modelClass)
            ->where('borrower_id', $borrower->id)
            ->orderBy('issue_date', 'desc')
            ->get();

        return view('backend.pages.sms.library.issues.borrower_history', compact('borrower', 'issues', 'type'));
    }
}
