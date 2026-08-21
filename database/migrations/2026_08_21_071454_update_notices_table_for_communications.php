<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->json('target_roles')->nullable()->after('description');
            $table->json('target_classes')->nullable()->after('target_roles');
            $table->json('target_sections')->nullable()->after('target_classes');
            $table->json('target_users')->nullable()->after('target_sections');
            $table->string('status')->default('draft')->after('target_users');
            $table->timestamp('published_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn(['target_roles', 'target_classes', 'target_sections', 'target_users', 'status', 'published_at']);
        });
    }
};
