<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('category')->default('other')->after('event_type');
            // start_time / end_time stored as time strings
            $table->time('start_time')->nullable()->after('visit_date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->date('end_date')->nullable()->after('end_time'); // multi-day support
            $table->boolean('registration_open')->default(false)->after('end_date');
            $table->date('registration_deadline')->nullable()->after('registration_open');
            $table->unsignedInteger('max_participants')->nullable()->after('registration_deadline');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['category', 'start_time', 'end_time', 'end_date', 'registration_open', 'registration_deadline', 'max_participants']);
        });
    }
};
