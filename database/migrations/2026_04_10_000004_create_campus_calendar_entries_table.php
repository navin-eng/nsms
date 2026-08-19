<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campus_calendar_entries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('entry_type')->default('other');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('result_link')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('campus_calendar_entries');
    }
};
