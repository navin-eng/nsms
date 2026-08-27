<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;

class EnsureModuleEnabled
{
    public function handle(Request $request, Closure $next, ?string $module = null)
    {
        $school = TenantContext::school();
        $module = $module ?: $this->moduleForRequest($request);

        if ($school && $module && !$school->hasModule($module)) {
            abort(403, 'This module is not enabled for your school package.');
        }

        return $next($request);
    }

    private function moduleForRequest(Request $request): ?string
    {
        $path = trim($request->path(), '/');

        $map = [
            'admin/dashboard/course' => 'website_cms',
            'admin/dashboard/testimonial' => 'website_cms',
            'admin/dashboard/gallery' => 'website_cms',
            'admin/dashboard/event' => 'website_cms',
            'admin/dashboard/notice' => 'website_cms',
            'admin/dashboard/banner' => 'website_cms',
            'admin/dashboard/page' => 'website_cms',
            'admin/dashboard/college-message' => 'website_cms',
            'admin/dashboard/message' => 'website_cms',
            'admin/dashboard/website-settings' => 'website_cms',
            'admin/dashboard/navbar' => 'website_cms',
            'admin/sms/admissions' => 'student_management',
            'admin/sms/students' => 'student_management',
            'admin/sms/staff' => 'student_management',
            'admin/sms/departments' => 'student_management',
            'admin/sms/designations' => 'student_management',
            'admin/sms/academic-years' => 'academic_structure',
            'admin/sms/academic-classes' => 'academic_structure',
            'admin/sms/sections' => 'academic_structure',
            'admin/sms/subjects' => 'academic_structure',
            'admin/sms/streams' => 'academic_structure',
            'admin/sms/assignments' => 'academic_structure',
            'admin/sms/periods' => 'academic_structure',
            'admin/sms/timetable' => 'academic_structure',
            'admin/sms/attendance' => 'attendance',
            'admin/sms/staff-attendance' => 'attendance',
            'admin/sms/leave-requests' => 'attendance',
            'admin/sms/exams' => 'examinations',
            'admin/sms/accounting' => 'double_entry_accounting',
            'admin/sms/hostel' => 'hostel',
            'admin/sms/library' => 'library',
            'admin/sms/homework' => 'communication',
            'admin/sms/materials' => 'communication',
            'admin/sms/school-notices' => 'communication',
            'admin/sms/communications' => 'communication',
            'admin/inventory' => 'inventory',
            'accounting/fees' => 'finance_billing',
            'accounting' => 'double_entry_accounting',
        ];

        foreach ($map as $prefix => $module) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return $module;
            }
        }

        return null;
    }
}
