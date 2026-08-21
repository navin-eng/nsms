<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admission_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_application_id')->constrained('admission_applications')->onDelete('cascade');
            $table->string('title');
            $table->string('document_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admission_documents');
    }
};

