<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\Student;
use App\Models\Staff;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Pratiksh\Nepalidate\Services\NepaliDate;
use Pratiksh\Nepalidate\Services\EnglishDate;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the leave requests.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'Pending');
        $userType = $request->input('user_type', 'all');

        $query = LeaveRequest::with('leavable')->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($userType === 'student') {
            $query->where('leavable_type', Student::class);
        } elseif ($userType === 'staff') {
            $query->where('leavable_type', Staff::class);
        }

        $leaveRequests = $query->paginate(20)->withQueryString();

        $students = Student::where('status', 'Active')->get();
        $staffMembers = Staff::where('status', 'Active')->get();

        $calendarSystem = SiteSetting::current()->calendar_system ?? 'AD';

        return view('backend.pages.sms.leave-requests.index', compact(
            'leaveRequests', 'status', 'userType', 'students', 'staffMembers', 'calendarSystem'
        ));
    }

    /**
     * Store a newly created leave request manually by admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:student,staff',
            'user_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        $leavableType = $request->user_type === 'student' ? Student::class : Staff::class;

        LeaveRequest::create([
            'leavable_type' => $leavableType,
            'leavable_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', 'Leave request created successfully.');
    }

    /**
     * Update the specified leave request (Approve/Reject).
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected',
            'remarks' => 'nullable|string',
        ]);

        $leaveRequest->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified leave request.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return back()->with('success', 'Leave request deleted successfully.');
    }
}
