<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProviderUser;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProviderAndTenantSeeder extends Seeder
{
    public function run()
    {
        // 0. Seed Provider Settings
        \App\Models\ProviderSetting::set('company_name', 'Nepal School Management System (NSMS)');
        \App\Models\ProviderSetting::set('company_address', 'Kathmandu, Nepal');
        \App\Models\ProviderSetting::set('company_pan_vat', 'PAN-123456789');
        \App\Models\ProviderSetting::set('tax_type', 'exclusive'); // exclusive or inclusive
        \App\Models\ProviderSetting::set('tax_rate', '13'); // 13% VAT

        // 1. Seed Provider Roles & Permissions
        $guard = 'provider';
        
        $permissions = [
            'provider_manage_users', // Create/edit other provider users
            'provider_manage_schools', // Create/edit basic school info
            'provider_manage_modules', // Edit module entitlements
            'provider_manage_billing', // View/edit subscription dates
            'provider_support_tools', // Reset passwords, view logs, change status
            'provider_technical_tools' // Feature flags, system settings
        ];

        foreach (['default', 'provider'] as $conn) {
            $permQuery = $conn === 'default' ? \Spatie\Permission\Models\Permission::query() : \App\Models\ProviderPermission::query();
            $roleQuery = $conn === 'default' ? \Spatie\Permission\Models\Role::query() : \App\Models\ProviderRole::query();

            foreach ($permissions as $permission) {
                $permQuery->clone()->firstOrCreate(['name' => $permission, 'guard_name' => $guard]);
            }

            // Roles
            $superAdminRole = $roleQuery->clone()->firstOrCreate(['name' => 'Provider Super Admin', 'guard_name' => $guard]);
            $superAdminRole->syncPermissions($permQuery->clone()->where('guard_name', $guard)->get());

            $supportRole = $roleQuery->clone()->firstOrCreate(['name' => 'Provider Support', 'guard_name' => $guard]);
            $supportRole->syncPermissions($permQuery->clone()->whereIn('name', ['provider_manage_schools', 'provider_support_tools'])->where('guard_name', $guard)->get());

            $billingRole = $roleQuery->clone()->firstOrCreate(['name' => 'Provider Billing', 'guard_name' => $guard]);
            $billingRole->syncPermissions($permQuery->clone()->whereIn('name', ['provider_manage_schools', 'provider_manage_billing'])->where('guard_name', $guard)->get());

            $techRole = $roleQuery->clone()->firstOrCreate(['name' => 'Provider Technical', 'guard_name' => $guard]);
            $techRole->syncPermissions($permQuery->clone()->whereIn('name', ['provider_manage_schools', 'provider_technical_tools', 'provider_support_tools'])->where('guard_name', $guard)->get());
        }

        // 1. Seed Central SaaS Provider Super Admin (God Mode)
        $providerAdmin = ProviderUser::firstOrCreate(
            ['email' => 'subscribe.navin@gmail.com'],
            [
                'name' => 'SaaS Provider Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'Super Admin',
                'phone' => '+977 9800000000',
                'is_active' => true,
            ]
        );
        $providerAdmin->assignRole($superAdminRole);

        // Seed other roles
        $supportUser = ProviderUser::firstOrCreate(
            ['email' => 'support@nsms.cloud'],
            [
                'name' => 'Support Agent',
                'password' => Hash::make('support123'),
                'role' => 'Support',
                'phone' => '+977 9800000001',
                'is_active' => true,
            ]
        );
        $supportUser->assignRole($supportRole);

        $billingUser = ProviderUser::firstOrCreate(
            ['email' => 'billing@nsms.cloud'],
            [
                'name' => 'Billing Agent',
                'password' => Hash::make('billing123'),
                'role' => 'Billing',
                'phone' => '+977 9800000002',
                'is_active' => true,
            ]
        );
        $billingUser->assignRole($billingRole);

        $techUser = ProviderUser::firstOrCreate(
            ['email' => 'tech@nsms.cloud'],
            [
                'name' => 'Technical Agent',
                'password' => Hash::make('tech123'),
                'role' => 'Technical',
                'phone' => '+977 9800000003',
                'is_active' => true,
            ]
        );
        $techUser->assignRole($techRole);

        // 2. Seed Default Demo School Tenant
        $demoSchool = School::firstOrCreate(
            ['school_code' => 'SCH-000101'],
            [
                'name' => 'Green Peace Lincoln College',
                'slug' => 'green-peace-lincoln-college',
                'contact_email' => 'info@gplc.edu.np',
                'contact_phone' => '+977 25 580000',
                'address' => 'Itahari, Sunsari, Nepal',
                'status' => 'active',
                'package_name' => 'Enterprise',
                'subscription_start' => now()->startOfYear(),
                'subscription_end' => now()->addYears(2),
                'enabled_modules' => array_keys(School::allModules()),
                'feature_flags' => [
                    'nepali_bikram_sambat' => true,
                    'double_entry_accounting' => true,
                    'sms_gateway' => true,
                ],
                'settings' => [
                    'currency' => 'NPR',
                    'academic_calendar' => 'BS',
                ],
            ]
        );

        // Seed a default Tenant Super Admin for the Demo School
        $tenantAdmin = User::firstOrCreate(
            ['email' => 'admin@gplc.edu.np'],
            [
                'name' => 'Demo School Admin',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'a_type' => 'A',
                'role' => 'Super Admin',
                'school_id' => $demoSchool->id,
                'image' => 'default.png',
            ]
        );
        $tenantAdmin->assignRole('Super Admin');

        // Link any other existing legacy users to this demo school
        User::whereNull('school_id')->update(['school_id' => $demoSchool->id]);
    }
}
