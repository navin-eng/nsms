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
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('admission_no')->unique(); // e.g., ADM-001
            $table->date('admission_date')->nullable();
            
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('category')->nullable(); // General, OBC, SC, ST, etc.
            
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            
            $table->string('photo')->nullable();
            $table->text('previous_school_details')->nullable(); // Can store as JSON or text
            
            $table->foreignId('guardian_id')->constrained('guardians')->onDelete('cascade');
            
            $table->string('status')->default('Active'); // Active, Graduated, Transferred, Dropped
            $table->unsignedBigInteger('user_id')->nullable(); // For future student portal

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
