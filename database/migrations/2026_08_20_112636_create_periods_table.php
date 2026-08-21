<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Period 1", "Lunch Break"
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_break')->default(false); // True for lunch/recess
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('periods');
    }
};
