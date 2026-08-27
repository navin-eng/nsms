<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'fee_types', 'fee_discounts', 'fee_invoice_items',
            'exam_marks', 'exam_schedules',
            'staff_attendances',
            'hostels', 'hostel_beds', 'hostel_attendances',
            'sections', 'subjects', 'periods', 'streams', 'departments', 'designations',
            'class_subject_assignments', 'timetable_entries',
            'notices', 'homework', 'homework_submissions', 'study_materials',
            'library_books', 'library_book_copies', 'library_categories', 'library_issues', 'library_settings',
            'inventory_categories', 'inventory_items', 'inventory_stores', 'inventory_suppliers',
            'inventory_purchases', 'inventory_issues', 'inventory_maintenances',
            'enrollments', 'guardians', 'student_documents', 'staff_documents', 'student_kudos',
            'grading_rules', 'certificates', 'id_card_templates',
            'events', 'event_participants', 'leave_requests',
            'messages', 'communications', 'communication_configs', 'communication_templates',
            'admission_applications', 'admission_documents', 'admission_enquiries',
            'activity_logs', 'campus_calendar_entries',
            'accounts', 'account_groups', 'bank_accounts', 'budgets',
            'expenses', 'vendors', 'journal_entries', 'journal_entry_items'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'school_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->nullOnDelete();
                });
                
                // Assign existing records to school 1 (Green Peace Lincoln College)
                DB::table($tableName)->whereNull('school_id')->update(['school_id' => 1]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = [
            'fee_types', 'fee_discounts', 'fee_invoice_items',
            'exam_marks', 'exam_schedules',
            'staff_attendances',
            'hostels', 'hostel_beds', 'hostel_attendances',
            'sections', 'subjects', 'periods', 'streams', 'departments', 'designations',
            'class_subject_assignments', 'timetable_entries',
            'notices', 'homework', 'homework_submissions', 'study_materials',
            'library_books', 'library_book_copies', 'library_categories', 'library_issues', 'library_settings',
            'inventory_categories', 'inventory_items', 'inventory_stores', 'inventory_suppliers',
            'inventory_purchases', 'inventory_issues', 'inventory_maintenances',
            'enrollments', 'guardians', 'student_documents', 'staff_documents', 'student_kudos',
            'grading_rules', 'certificates', 'id_card_templates',
            'events', 'event_participants', 'leave_requests',
            'messages', 'communications', 'communication_configs', 'communication_templates',
            'admission_applications', 'admission_documents', 'admission_enquiries',
            'activity_logs', 'campus_calendar_entries',
            'accounts', 'account_groups', 'bank_accounts', 'budgets',
            'expenses', 'vendors', 'journal_entries', 'journal_entry_items'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'school_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropConstrainedForeignId('school_id');
                });
            }
        }
    }
};
