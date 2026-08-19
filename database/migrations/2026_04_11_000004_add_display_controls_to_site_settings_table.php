<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings', 'show_topbar')) {
                $table->boolean('show_topbar')->default(true)->after('sticky_notice_limit');
            }

            if (!Schema::hasColumn('site_settings', 'show_whatsapp_button')) {
                $table->boolean('show_whatsapp_button')->default(true)->after('show_topbar');
            }

            if (!Schema::hasColumn('site_settings', 'show_back_to_top')) {
                $table->boolean('show_back_to_top')->default(true)->after('show_whatsapp_button');
            }

            if (!Schema::hasColumn('site_settings', 'sticky_notice_desktop_collapsed')) {
                $table->boolean('sticky_notice_desktop_collapsed')->default(false)->after('show_back_to_top');
            }

            if (!Schema::hasColumn('site_settings', 'sticky_notice_mobile_collapsed')) {
                $table->boolean('sticky_notice_mobile_collapsed')->default(true)->after('sticky_notice_desktop_collapsed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            foreach ([
                'sticky_notice_mobile_collapsed',
                'sticky_notice_desktop_collapsed',
                'show_back_to_top',
                'show_whatsapp_button',
                'show_topbar',
            ] as $column) {
                if (Schema::hasColumn('site_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
