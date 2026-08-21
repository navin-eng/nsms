<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('module_name')->nullable()->after('name');
        });

        // Seed module names for existing permissions
        $permissions = DB::table('permissions')->get();
        foreach ($permissions as $permission) {
            $moduleName = 'General';
            
            // Map the old manage_* prefix to a module name
            if (str_starts_with($permission->name, 'manage_')) {
                $raw = str_replace('manage_', '', $permission->name);
                $moduleName = ucwords(str_replace('_', ' ', $raw));
            }

            DB::table('permissions')->where('id', $permission->id)->update([
                'module_name' => $moduleName
            ]);
        }
    }

    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('module_name');
        });
    }
};
