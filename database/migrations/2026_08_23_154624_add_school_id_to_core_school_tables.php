<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            'students',
            'staff',
            'academic_classes',
            'academic_years',
            'fee_structures',
            'fee_invoices',
            'fee_payments',
            'exams',
            'student_attendances',
            'hostel_rooms',
            'hostel_allocations',
            'accountants',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'school_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->nullOnDelete();
                });
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
            'students',
            'staff',
            'academic_classes',
            'academic_years',
            'fee_structures',
            'fee_invoices',
            'fee_payments',
            'exams',
            'student_attendances',
            'hostel_rooms',
            'hostel_allocations',
            'accountants',
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
