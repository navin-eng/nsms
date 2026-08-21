<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('user_name');
            $table->string('ip_address', 45)->nullable()->after('user_id');
            $table->string('location')->nullable()->after('ip_address'); // country/city resolved lazily
            $table->text('user_agent')->nullable()->after('location');
            $table->string('model_type')->nullable()->after('summary');
            $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            $table->json('properties')->nullable()->after('model_id'); // before/after diff
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'ip_address', 'location', 'user_agent', 'model_type', 'model_id', 'properties']);
        });
    }
};
