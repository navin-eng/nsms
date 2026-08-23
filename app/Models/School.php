<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_code',
        'name',
        'slug',
        'contact_email',
        'contact_phone',
        'address',
        'logo',
        'status',
        'package_name',
        'subscription_start',
        'subscription_end',
        'enabled_modules',
        'feature_flags',
        'settings',
        'admin_notes',
    ];

    protected $casts = [
        'enabled_modules' => 'array',
        'feature_flags' => 'array',
        'settings' => 'array',
        'subscription_start' => 'date',
        'subscription_end' => 'date',
    ];

    /**
     * Default list of all available system modules
     */
    public static function allModules(): array
    {
        return [
            'student_management' => 'Student Lifecycle & Admissions',
            'academic_structure' => 'Classes, Sections & Subjects',
            'attendance' => 'Student & Staff Daily Attendance',
            'examinations' => 'Exams, GPA & Digital Marksheets',
            'finance_billing' => 'Bikram Sambat Fee Invoicing & Receipts',
            'double_entry_accounting' => 'General Ledger, P&L & Balance Sheet',
            'hostel' => 'Hostel Rooms & Occupancy',
            'transportation' => 'Fleet & Transport Routes',
            'website_cms' => 'School Public Website CMS',
            'qr_verification' => 'QR Code Document Verification',
        ];
    }

    /**
     * Generate unique school code e.g. SCH-000101
     */
    public static function generateUniqueCode(): string
    {
        $lastSchool = self::orderBy('id', 'desc')->first();
        $nextId = $lastSchool ? ($lastSchool->id + 1) : 101;
        $code = 'SCH-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        while (self::where('school_code', $code)->exists()) {
            $nextId++;
            $code = 'SCH-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    /**
     * Relationships
     */
    public function users()
    {
        return $this->hasMany(User::class, 'school_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'school_id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, 'school_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(ProviderAuditLog::class, 'school_id');
    }

    /**
     * Check if a module is enabled for this school
     */
    public function hasModule(string $moduleKey): bool
    {
        if (empty($this->enabled_modules)) {
            return false;
        }

        return in_array($moduleKey, $this->enabled_modules, true);
    }

    /**
     * Status check helper
     */
    public function isOperational(): bool
    {
        return in_array($this->status, ['active', 'trial'], true);
    }
}
