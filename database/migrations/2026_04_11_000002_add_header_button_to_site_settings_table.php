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
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings', 'header_button_text')) {
                $table->string('header_button_text')->nullable()->after('gallery_layout');
            }

            if (!Schema::hasColumn('site_settings', 'header_button_url')) {
                $table->string('header_button_url')->nullable()->after('header_button_text');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (Schema::hasColumn('site_settings', 'header_button_url')) {
                $table->dropColumn('header_button_url');
            }

            if (Schema::hasColumn('site_settings', 'header_button_text')) {
                $table->dropColumn('header_button_text');
            }
        });
    }
};
