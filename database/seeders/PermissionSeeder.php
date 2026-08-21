<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Core system permissions
        $permissions = [
            'manage_users',
            'manage_roles',
            'manage_audit_logs',
            
            // CMS Permissions
            'manage_cms_content',
            'manage_website_settings',
            
            // SMS Permissions
            'manage_academic_structure',
            'manage_staff',
            'manage_students',
            'manage_attendance',
            'manage_exams',
            'manage_school_info'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Super Admin role and assign all permissions
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->givePermissionTo(Permission::all());
        
        // Assign Super Admin to user ID 1 if exists
        $user = \App\Models\User::find(1);
        if ($user && !$user->hasRole('Super Admin')) {
            $user->assignRole('Super Admin');
        }
    }
}
