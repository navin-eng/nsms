<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $module = $request->input('module', 'all');
        $action = $request->input('action', 'all');
        $search = $request->input('search');
        $from   = $request->input('from');
        $to     = $request->input('to');

        $query = ActivityLog::orderBy('created_at', 'desc');

        if ($module !== 'all') $query->where('module', $module);
        if ($action !== 'all') $query->where('action', $action);
        if ($search)           $query->where(function($q) use ($search) {
            $q->where('user_name', 'like', "%{$search}%")
              ->orWhere('summary', 'like', "%{$search}%")
              ->orWhere('ip_address', 'like', "%{$search}%");
        });
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to)   $query->whereDate('created_at', '<=', $to);

        $logs    = $query->paginate(50)->withQueryString();
        $modules = ActivityLog::select('module')->distinct()->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('backend.pages.activity-logs.index',
            compact('logs', 'module', 'action', 'modules', 'actions', 'search', 'from', 'to'));
    }

    public function show(ActivityLog $activityLog)
    {
        return view('backend.pages.activity-logs.show', ['log' => $activityLog]);
    }

    public function export(Request $request)
    {
        $module = $request->input('module', 'all');
        $action = $request->input('action', 'all');
        $search = $request->input('search');
        $from   = $request->input('from');
        $to     = $request->input('to');

        $query = ActivityLog::orderBy('created_at', 'desc');
        if ($module !== 'all') $query->where('module', $module);
        if ($action !== 'all') $query->where('action', $action);
        if ($search)           $query->where(function($q) use ($search) {
            $q->where('user_name', 'like', "%{$search}%")
              ->orWhere('summary', 'like', "%{$search}%");
        });
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to)   $query->whereDate('created_at', '<=', $to);

        $logs = $query->get();

        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['#', 'Timestamp', 'User', 'Module', 'Action', 'Summary', 'IP Address', 'User Agent']);
            foreach ($logs as $i => $log) {
                fputcsv($handle, [
                    $i + 1,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user_name,
                    $log->module,
                    $log->action,
                    $log->summary,
                    $log->ip_address ?? '—',
                    $log->user_agent ?? '—',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
