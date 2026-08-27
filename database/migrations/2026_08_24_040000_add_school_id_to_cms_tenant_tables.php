<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tables = [
            'about_us',
            'about_us_faqs',
            'banners',
            'college_messages',
            'counters',
            'courses',
            'galleries',
            'home_sections',
            'navbar_items',
            'pages',
            'privacypolicies',
            'site_settings',
            'social_media_settings',
            'testimonials',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'school_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->nullOnDelete();
                });

                DB::table($tableName)->whereNull('school_id')->update(['school_id' => 1]);
            }
        }
    }

    public function down()
    {
        $tables = [
            'about_us',
            'about_us_faqs',
            'banners',
            'college_messages',
            'counters',
            'courses',
            'galleries',
            'home_sections',
            'navbar_items',
            'pages',
            'privacypolicies',
            'site_settings',
            'social_media_settings',
            'testimonials',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'school_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropConstrainedForeignId('school_id');
                });
            }
        }
    }
};
