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
        Schema::create('hostel_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_allocation_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['Present', 'Absent', 'Leave', 'Late']);
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->unique(['hostel_allocation_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hostel_attendances');
    }
};
