<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProviderUser;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProviderAndTenantSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Central SaaS Provider Super Admin (God Mode)
        $providerAdmin = ProviderUser::firstOrCreate(
            ['email' => 'godmode@nsms.cloud'],
            [
                'name' => 'SaaS Provider Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'Super Admin',
                'phone' => '+977 9800000000',
                'is_active' => true,
            ]
        );

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

        // Link existing users to this demo school
        User::whereNull('school_id')->update(['school_id' => $demoSchool->id]);
    }
}
