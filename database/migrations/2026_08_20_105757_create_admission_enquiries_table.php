<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admission_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->foreignId('academic_class_id')->nullable()->constrained('academic_classes')->onDelete('set null');
            $table->string('source')->nullable(); // Walk-in, Phone, Website, Referral
            $table->string('status')->default('Open'); // Open, Followed Up, Closed
            $table->text('notes')->nullable();
            $table->date('enquiry_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admission_enquiries');
    }
};

