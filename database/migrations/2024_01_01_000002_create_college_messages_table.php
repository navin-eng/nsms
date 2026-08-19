<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('college_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation')->comment('e.g. Principal, Chairman, Coordinator');
            $table->text('message');
            $table->string('image')->nullable();
            $table->integer('order')->default(0)->comment('Display order');
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=inactive');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('college_messages');
    }
};
