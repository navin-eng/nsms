<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Green Peace Lincoln College');
            $table->string('site_short_name')->default('GPLC');
            $table->string('site_tagline')->default('Affiliated with Lincoln University Malaysia');
            $table->string('primary_color')->default('#2d6a4f');
            $table->string('primary_dark')->default('#1a472a');
            $table->string('primary_light')->default('#40916c');
            $table->string('accent_color')->default('#52b788');
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('gallery_layout')->default('masonry');
            $table->timestamps();
        });

        DB::table('site_settings')->insert([
            'site_name' => 'Green Peace Lincoln College',
            'site_short_name' => 'GPLC',
            'site_tagline' => 'Affiliated with Lincoln University Malaysia',
            'primary_color' => '#2d6a4f',
            'primary_dark' => '#1a472a',
            'primary_light' => '#40916c',
            'accent_color' => '#52b788',
            'contact_phone' => '025-586701',
            'contact_email' => 'info@gplc.edu.np',
            'contact_address' => 'Itahari-2, Sunsari, Nepal',
            'whatsapp_number' => '9812355717',
            'facebook_url' => 'https://www.facebook.com/GplcIth',
            'youtube_url' => '#',
            'instagram_url' => '#',
            'gallery_layout' => 'masonry',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('site_settings');
    }
};
