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
        Schema::create('library_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('max_borrow_days_student')->default(7);
            $table->integer('max_borrow_days_staff')->default(14);
            $table->decimal('fine_per_day', 8, 2)->default(5.00);
            $table->integer('max_books_student')->default(2);
            $table->integer('max_books_staff')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_settings');
    }
};
