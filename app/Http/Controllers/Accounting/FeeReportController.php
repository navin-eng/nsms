<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\FeeInvoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeReportController extends Controller
{
    public function outstanding(Request $request)
    {
        $years = AcademicYear::orderByDesc('start_date')->get();
        $classes = AcademicClass::orderBy('numeric_value')->get();
        $months = [
            'Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin',
            'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'
        ];

        $query = FeeInvoice::with(['student.enrollments.academicClass', 'student.enrollments.section', 'academicYear'])
            ->where(function ($q) {
                $q->whereIn('status', ['Unpaid', 'Partial'])
                  ->orWhereRaw('(total_amount - paid_amount) > 0');
            });

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('nepali_month')) {
            $query->where('nepali_month', $request->nepali_month);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('academic_class_id')) {
            $classId = $request->academic_class_id;
            $query->whereHas('student.enrollments', function ($q) use ($classId) {
                $q->where('academic_class_id', $classId);
            });
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhereHas('student', function ($sq) use ($term) {
                      $sq->where('first_name', 'like', "%{$term}%")
                         ->orWhere('last_name', 'like', "%{$term}%")
                         ->orWhere('registration_number', 'like', "%{$term}%");
                  });
            });
        }

        // Clone query for overall totals
        $totalBilled = (clone $query)->sum('total_amount');
        $totalPaid = (clone $query)->sum('paid_amount');
        $totalOutstanding = $totalBilled - $totalPaid;
        $totalCount = (clone $query)->count();

        $invoices = $query->latest()->paginate(25)->withQueryString();

        return view('accounting.fees.reports.outstanding', compact(
            'invoices',
            'years',
            'classes',
            'months',
            'totalBilled',
            'totalPaid',
            'totalOutstanding',
            'totalCount'
        ));
    }
}
