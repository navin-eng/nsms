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
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('consent_birthday_post')->default(true)->after('status');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->boolean('consent_birthday_post')->default(true)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('consent_birthday_post');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('consent_birthday_post');
        });
    }
};
