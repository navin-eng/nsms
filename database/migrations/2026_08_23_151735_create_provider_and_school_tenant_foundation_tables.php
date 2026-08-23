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
        // 1. SaaS Provider Users (God-Mode team)
        Schema::create('provider_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('Super Admin'); // Super Admin, Support, Billing, Technical
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. School Tenants
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_code', 32)->unique()->index(); // e.g. SCH-000101
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            
            // Operational Lifecycle Status
            $table->enum('status', [
                'pending', 
                'trial', 
                'active', 
                'suspended', 
                'disabled', 
                'expired', 
                'archived'
            ])->default('active')->index();

            // Subscription & Package
            $table->string('package_name')->default('Professional'); // Basic, Professional, Enterprise, Custom
            $table->date('subscription_start')->nullable();
            $table->date('subscription_end')->nullable();

            // Module Entitlements JSON (Academic, Finance, Attendance, Exams, Hostel, Transport, Website, etc.)
            $table->json('enabled_modules')->nullable();

            // Feature Flags JSON
            $table->json('feature_flags')->nullable();

            // Settings JSON (Custom calendar type, currency, receipt formats, etc.)
            $table->json('settings')->nullable();

            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 3. Add school_id to users table if not present
        if (!Schema::hasColumn('users', 'school_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->nullOnDelete();
                $table->string('username')->nullable()->after('email');
            });
        }

        // 4. Provider Audit Logs
        Schema::create('provider_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_user_id')->nullable()->constrained('provider_users')->nullOnDelete();
            $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->string('action'); // e.g. school.created, school.suspended, modules.updated
            $table->text('description')->nullable();
            $table->json('payload_before')->nullable();
            $table->json('payload_after')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'school_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('school_id');
                $table->dropColumn('username');
            });
        }
        Schema::dropIfExists('provider_audit_logs');
        Schema::dropIfExists('schools');
        Schema::dropIfExists('provider_users');
    }
};
