<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Staff;
use App\Models\FeePayment;
use App\Models\FeeInvoice;
use App\Models\StudentAttendance;
use App\Models\ExamMark;
use App\Models\InventoryPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display Academic Analytics.
     */
    public function academic()
    {
        abort_if(!auth()->user()->can('view_academic_analytics'), 403, 'Unauthorized');

        // Simple mock of pass/fail if data is missing, but try to get real data.
        $totalMarks = ExamMark::count();
        
        $passCount = ExamMark::where('passed', true)->count();
        $failCount = ExamMark::where('passed', false)->count();

        if ($totalMarks == 0) {
            $passCount = 0;
            $failCount = 0;
        }

        $passFailChart = [
            'labels' => ['Passed', 'Failed'],
            'data' => [$passCount, $failCount]
        ];

        // Average scores by subject (Top 5)
        $subjectAverages = ExamMark::join('subjects', 'exam_marks.subject_id', '=', 'subjects.id')
            ->select('subjects.name', DB::raw('AVG(exam_marks.marks_obtained) as average_score'))
            ->groupBy('subjects.id', 'subjects.name')
            ->orderByDesc('average_score')
            ->take(5)
            ->get();

        $subjectChart = [
            'labels' => $subjectAverages->pluck('name')->toArray(),
            'data' => $subjectAverages->pluck('average_score')->map(fn($val) => round($val, 1))->toArray()
        ];

        return view('backend.pages.analytics.academic', compact('passFailChart', 'subjectChart', 'totalMarks'));
    }

    /**
     * Display Attendance Analytics.
     */
    public function attendance()
    {
        abort_if(!auth()->user()->can('view_attendance_analytics'), 403, 'Unauthorized');

        // Last 7 days attendance trend
        $trendChart = ['labels' => [], 'data' => []];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $trendChart['labels'][] = $date->format('D, M d');
            
            $total = StudentAttendance::whereDate('attendance_date', $date)->count();
            $present = StudentAttendance::whereDate('attendance_date', $date)
                                      ->where('status', 'present')
                                      ->count();
            
            $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
            $trendChart['data'][] = $rate;
        }

        // Attendance by Status distribution (Overall)
        $statusCounts = StudentAttendance::select('status', DB::raw('count(*) as count'))
                                       ->groupBy('status')
                                       ->pluck('count', 'status')
                                       ->toArray();

        $distributionChart = [
            'labels' => array_keys($statusCounts),
            'data' => array_values($statusCounts)
        ];

        return view('backend.pages.analytics.attendance', compact('trendChart', 'distributionChart'));
    }

    /**
     * Display Financial Analytics.
     */
    public function financial()
    {
        abort_if(!auth()->user()->can('view_financial_analytics'), 403, 'Unauthorized');

        // 1. Revenue vs Inventory Expenses (Last 6 Months)
        $financialTrend = ['labels' => [], 'revenue' => [], 'expenses' => []];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            
            $monthName = $monthStart->format('M Y');
            
            $revenue = FeePayment::whereBetween('payment_date', [$monthStart, $monthEnd])->sum('amount');
            $expense = InventoryPurchase::whereBetween('purchase_date', [$monthStart, $monthEnd])->sum('total_price');
            
            $financialTrend['labels'][] = $monthName;
            $financialTrend['revenue'][] = $revenue;
            $financialTrend['expenses'][] = $expense;
        }

        // 2. Invoice Status Distribution
        $invoiceStats = FeeInvoice::select('status', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
                                ->groupBy('status')
                                ->get();

        $invoiceChart = [
            'labels' => [],
            'data' => []
        ];

        foreach($invoiceStats as $stat) {
            $invoiceChart['labels'][] = ucfirst($stat->status) . ' (' . number_format($stat->total, 2) . ')';
            $invoiceChart['data'][] = $stat->count;
        }

        return view('backend.pages.analytics.financial', compact('financialTrend', 'invoiceChart'));
    }
}
