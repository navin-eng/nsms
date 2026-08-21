<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityObserver
{
    public function created($model)
    {
        $this->logActivity('Created', $model);
    }

    public function updated($model)
    {
        if ($model->wasChanged() && !empty(array_diff(array_keys($model->getChanges()), ['updated_at']))) {
            $this->logActivity('Updated', $model, $model->getOriginal(), $model->getChanges());
        }
    }

    public function deleted($model)
    {
        $this->logActivity('Deleted', $model);
    }

    public function restored($model)
    {
        $this->logActivity('Restored', $model);
    }

    public function forceDeleted($model)
    {
        $this->logActivity('Force Deleted', $model);
    }

    private function logActivity($action, $model, array $before = [], array $after = [])
    {
        // Don't log activity log entries themselves (infinite loop prevention)
        if ($model instanceof ActivityLog) return;

        $className = class_basename($model);
        $module = $this->getModuleName($className);

        $user     = Auth::user();
        $userName = $user ? $user->name : 'System';
        $userId   = $user ? $user->id   : null;

        // Resolve IP — handles proxies
        $ip = Request::ip();
        if (Request::header('X-Forwarded-For')) {
            $ip = trim(explode(',', Request::header('X-Forwarded-For'))[0]);
        }

        $userAgent = Request::header('User-Agent', '');

        // Try to get a meaningful identifier for the summary
        $identifier = $model->name ?? $model->title ?? $model->full_name ?? $model->email ?? "ID: {$model->id}";
        $summary    = "{$action} {$className}: {$identifier}";

        // Build before/after diff (exclude timestamps and sensitive fields)
        $hidden   = ['password', 'remember_token', 'updated_at', 'created_at'];
        $cleanBefore = array_diff_key($before, array_flip($hidden));
        $cleanAfter  = array_diff_key($after,  array_flip($hidden));

        $properties = null;
        if (!empty($cleanBefore) || !empty($cleanAfter)) {
            $properties = [
                'before' => $cleanBefore,
                'after'  => $cleanAfter,
            ];
        }

        ActivityLog::create([
            'module'     => $module,
            'action'     => $action,
            'user_id'    => $userId,
            'user_name'  => $userName,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'summary'    => $summary,
            'model_type' => $className,
            'model_id'   => $model->id ?? null,
            'properties' => $properties,
        ]);
    }

    private function getModuleName($className)
    {
        $modules = [
            'Staff'            => 'HR',
            'StaffAttendance'  => 'HR',
            'Student'          => 'Academics',
            'StudentAttendance'=> 'Academics',
            'AcademicClass'    => 'Academics',
            'Section'          => 'Academics',
            'AcademicYear'     => 'Academics',
            'Subject'          => 'Academics',
            'Homework'         => 'Academics',
            'StudyMaterial'    => 'Academics',
            'Exam'             => 'Exams',
            'ExamResult'       => 'Exams',
            'ExamSchedule'     => 'Exams',
            'FeeStructure'     => 'Finance',
            'FeeInvoice'       => 'Finance',
            'FeePayment'       => 'Finance',
            'SiteSetting'      => 'Settings',
            'User'             => 'System',
            'Role'             => 'System',
            'Permission'       => 'System',
        ];

        return $modules[$className] ?? $className;
    }
}
