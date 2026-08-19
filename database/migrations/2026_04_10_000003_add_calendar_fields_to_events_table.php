<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('event_type')->default('event')->after('name');
            $table->string('venue')->nullable()->after('visit_date');
            $table->string('result_link')->nullable()->after('venue');
        });

        DB::table('events')->update([
            'event_type' => 'event',
            'venue' => 'GPLC Campus, Itahari',
        ]);
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_type', 'venue', 'result_link']);
        });
    }
};
