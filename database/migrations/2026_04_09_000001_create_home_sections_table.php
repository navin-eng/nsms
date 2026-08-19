<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('home_sections')->insert([
            ['key' => 'popup_notice', 'label' => 'Popup Notice', 'is_visible' => 1, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_banner', 'label' => 'Hero Banner', 'is_visible' => 1, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'affiliation_bar', 'label' => 'Affiliation Bar', 'is_visible' => 1, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'notice_ticker', 'label' => 'Notice Ticker', 'is_visible' => 1, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'courses', 'label' => 'Courses', 'is_visible' => 1, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'counter', 'label' => 'Counter', 'is_visible' => 1, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'official_messages', 'label' => 'Official Messages', 'is_visible' => 1, 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'events', 'label' => 'Events', 'is_visible' => 1, 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact', 'label' => 'Contact', 'is_visible' => 1, 'sort_order' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'testimonials', 'label' => 'Testimonials', 'is_visible' => 1, 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('home_sections');
    }
};
