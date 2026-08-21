<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\FeeInvoice;
use App\Models\FeePayment;

class FeeController extends Controller
{
    public function index()
    {
        $childId = session('active_child_id');
        $child = Student::findOrFail($childId);
        
        $invoices = FeeInvoice::where('student_id', $childId)
            ->orderByDesc('due_date')
            ->get();
            
        $payments = FeePayment::whereHas('invoice', function($q) use ($childId) {
                $q->where('student_id', $childId);
            })
            ->orderByDesc('payment_date')
            ->get();
            
        return view('parent.fees', compact('child', 'invoices', 'payments'));
    }
}
