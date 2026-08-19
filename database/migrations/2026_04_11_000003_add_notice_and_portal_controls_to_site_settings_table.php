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
            if (!Schema::hasColumn('site_settings', 'student_portal_text')) {
                $table->string('student_portal_text')->nullable()->after('header_button_url');
            }
            if (!Schema::hasColumn('site_settings', 'student_portal_url')) {
                $table->string('student_portal_url')->nullable()->after('student_portal_text');
            }
            if (!Schema::hasColumn('site_settings', 'show_sticky_notice')) {
                $table->boolean('show_sticky_notice')->default(true)->after('student_portal_url');
            }
            if (!Schema::hasColumn('site_settings', 'sticky_notice_title')) {
                $table->string('sticky_notice_title')->nullable()->after('show_sticky_notice');
            }
            if (!Schema::hasColumn('site_settings', 'sticky_notice_limit')) {
                $table->unsignedTinyInteger('sticky_notice_limit')->default(5)->after('sticky_notice_title');
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
            foreach (['sticky_notice_limit', 'sticky_notice_title', 'show_sticky_notice', 'student_portal_url', 'student_portal_text'] as $column) {
                if (Schema::hasColumn('site_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
