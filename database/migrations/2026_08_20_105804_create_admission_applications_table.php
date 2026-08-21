<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob');
            $table->string('gender');
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->foreignId('academic_class_id')->nullable()->constrained('academic_classes')->onDelete('set null');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('contact_number');
            $table->string('previous_school')->nullable();
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->date('application_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admission_applications');
    }
};

