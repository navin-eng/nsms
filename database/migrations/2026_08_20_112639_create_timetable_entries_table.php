<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('timetable_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('academic_class_id')->constrained('academic_classes')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('set null');
            $table->foreignId('period_id')->constrained('periods')->onDelete('cascade');
            $table->string('day_of_week'); // Monday, Tuesday, ...
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('staff')->onDelete('set null');
            $table->string('room')->nullable();
            // Custom time override — allows non-uniform durations per subject
            $table->time('custom_start_time')->nullable();
            $table->time('custom_end_time')->nullable();
            $table->timestamps();

            // A class+section can only have ONE subject per period per day
            $table->unique(['academic_class_id', 'section_id', 'period_id', 'day_of_week'], 'unique_class_period_day');
        });
    }

    public function down()
    {
        Schema::dropIfExists('timetable_entries');
    }
};
