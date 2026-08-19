<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->string('section_tag')->default('Our Impact')->after('id');
            $table->string('section_title')->default('Numbers That Reflect Our Growth')->after('section_tag');
            $table->string('section_description')->nullable()->after('section_title');
            $table->string('suffix1')->default('+')->after('counter1');
            $table->string('icon1')->default('fa-solid fa-users')->after('suffix1');
            $table->string('suffix2')->default('+')->after('counter2');
            $table->string('icon2')->default('fa-solid fa-graduation-cap')->after('suffix2');
            $table->string('suffix3')->default('+')->after('counter3');
            $table->string('icon3')->default('fa-solid fa-trophy')->after('suffix3');
            $table->string('suffix4')->default('+')->after('counter4');
            $table->string('icon4')->default('fa-solid fa-book')->after('suffix4');
        });
    }

    public function down()
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->dropColumn([
                'section_tag',
                'section_title',
                'section_description',
                'suffix1',
                'icon1',
                'suffix2',
                'icon2',
                'suffix3',
                'icon3',
                'suffix4',
                'icon4',
            ]);
        });
    }
};
