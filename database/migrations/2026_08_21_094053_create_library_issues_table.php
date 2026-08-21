<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('library_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_book_copy_id')->constrained()->cascadeOnDelete();
            
            // Polymorphic relation to allow Staff/Teacher/Student to borrow
            $table->morphs('borrower'); 
            
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            
            $table->decimal('fine_amount', 8, 2)->default(0);
            $table->string('fine_status')->nullable(); // paid, unpaid
            
            $table->string('status')->default('issued'); // issued, returned, overdue, lost
            
            $table->text('remarks')->nullable();
            
            // Track who issued it
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_issues');
    }
};
