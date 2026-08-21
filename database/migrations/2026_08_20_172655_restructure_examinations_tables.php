<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Drop old exam_results
        Schema::dropIfExists('exam_results');
        
        // 2. Modify exams table
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->date('start_date')->nullable()->after('status');
            $table->date('end_date')->nullable()->after('start_date');
            $table->text('description')->nullable()->after('end_date');
        });

        // 3. Exam Schedules
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->date('exam_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->decimal('theory_full_marks', 5, 2)->default(0);
            $table->decimal('theory_pass_marks', 5, 2)->default(0);
            $table->decimal('practical_full_marks', 5, 2)->default(0);
            $table->decimal('practical_pass_marks', 5, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['exam_id', 'academic_class_id', 'subject_id'], 'exam_schedule_unique');
        });

        // 4. Exam Marks
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->decimal('theory_marks', 5, 2)->nullable();
            $table->decimal('practical_marks', 5, 2)->nullable();
            $table->boolean('is_absent')->default(false);
            $table->string('remarks')->nullable();
            $table->timestamps();
            
            $table->unique(['exam_id', 'student_id', 'subject_id'], 'exam_marks_unique');
        });

        // 5. Grading Rules
        Schema::create('grading_rules', function (Blueprint $table) {
            $table->id();
            $table->string('grade_name', 10); // A+, A, B...
            $table->decimal('min_percent', 5, 2);
            $table->decimal('max_percent', 5, 2);
            $table->decimal('grade_point', 3, 2); // 4.0, 3.6...
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
        
        // Seed default grading rules
        DB::table('grading_rules')->insert([
            ['grade_name' => 'A+', 'min_percent' => 90, 'max_percent' => 100, 'grade_point' => 4.0, 'remarks' => 'Outstanding'],
            ['grade_name' => 'A', 'min_percent' => 80, 'max_percent' => 89.99, 'grade_point' => 3.6, 'remarks' => 'Excellent'],
            ['grade_name' => 'B+', 'min_percent' => 70, 'max_percent' => 79.99, 'grade_point' => 3.2, 'remarks' => 'Very Good'],
            ['grade_name' => 'B', 'min_percent' => 60, 'max_percent' => 69.99, 'grade_point' => 2.8, 'remarks' => 'Good'],
            ['grade_name' => 'C+', 'min_percent' => 50, 'max_percent' => 59.99, 'grade_point' => 2.4, 'remarks' => 'Satisfactory'],
            ['grade_name' => 'C', 'min_percent' => 40, 'max_percent' => 49.99, 'grade_point' => 2.0, 'remarks' => 'Acceptable'],
            ['grade_name' => 'D+', 'min_percent' => 30, 'max_percent' => 39.99, 'grade_point' => 1.6, 'remarks' => 'Partially Acceptable'],
            ['grade_name' => 'D', 'min_percent' => 20, 'max_percent' => 29.99, 'grade_point' => 1.2, 'remarks' => 'Insufficient'],
            ['grade_name' => 'E', 'min_percent' => 0, 'max_percent' => 19.99, 'grade_point' => 0.8, 'remarks' => 'Very Insufficient'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('grading_rules');
        Schema::dropIfExists('exam_marks');
        Schema::dropIfExists('exam_schedules');
        
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn(['academic_year_id', 'start_date', 'end_date', 'description']);
        });
        
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->string('student_name');
            $table->string('symbol_number');
            $table->json('marks_data');
            $table->timestamps();
        });
    }
};
