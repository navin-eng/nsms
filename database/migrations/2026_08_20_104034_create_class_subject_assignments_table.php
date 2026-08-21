<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_subject_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('academic_class_id')->constrained('academic_classes')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('set null');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('staff')->onDelete('set null'); // Assigned teacher
            $table->integer('weekly_periods')->nullable()->comment('Number of periods per week');
            $table->timestamps();

            // Prevent duplicate assignment of same subject to same class in same year
            $table->unique(['academic_year_id', 'academic_class_id', 'section_id', 'subject_id'], 'unique_class_subject');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_subject_assignments');
    }
};

