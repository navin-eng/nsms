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
        if (!Schema::hasColumn('homework', 'total_marks')) {
            Schema::table('homework', function (Blueprint $table) {
                $table->decimal('total_marks', 8, 2)->nullable()->default(100)->after('status');
            });
        }

        Schema::create('homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homework_id')->constrained('homework')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->timestamp('submission_date')->useCurrent();
            $table->string('file_path')->nullable();
            $table->text('comments')->nullable();
            $table->string('status')->default('submitted'); // submitted, late, graded, resubmission
            $table->decimal('marks_obtained', 8, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->unique(['homework_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homework_submissions');
        
        if (Schema::hasColumn('homework', 'total_marks')) {
            Schema::table('homework', function (Blueprint $table) {
                $table->dropColumn('total_marks');
            });
        }
    }
};
