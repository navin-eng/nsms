<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Support\TenantContext;

class ModuleEntitlementsTest extends TestCase
{
    use RefreshDatabase;

    protected $school;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test school with Basic package
        $this->school = School::create([
            'name' => 'Test School ' . \Illuminate\Support\Str::random(4),
            'slug' => 'test-school-' . \Illuminate\Support\Str::random(6),
            'school_code' => 'TEST' . \Illuminate\Support\Str::random(4),
            'package_name' => 'Basic',
            'status' => 'active',
            'subscription_end' => now()->addDays(30),
            'enabled_modules' => ['student_management'],
        ]);

        // Create a user for the school
        $this->user = User::create([
            'name' => 'Test Admin',
            'email' => 'admin_' . \Illuminate\Support\Str::random(4) . '@test.com',
            'password' => Hash::make('password'),
            'school_id' => $this->school->id,
            'role' => 'admin',
            'image' => 'default.png',
        ]);
    }

    public function test_enabled_module_allows_access()
    {
        // Basic package typically has 'student_management' enabled (based on defaults).
        // Let's set it explicitly if needed, but we can assume School model methods use defaults if not overridden.
        // Assuming we have a route like 'admin/sms/students' that maps to 'student_management'.

        $this->actingAs($this->user);

        // Define a test route to hit the middleware
        \Route::get('admin/sms/students', function () {
            return response('OK');
        })->middleware(['web', 'auth', \App\Http\Middleware\EnsureModuleEnabled::class]);

        $response = $this->get('admin/sms/students');
        
        $response->assertStatus(200);
        $response->assertSee('OK');
    }

    public function test_disabled_module_returns_403()
    {
        $this->actingAs($this->user);

        // Basic package does NOT typically have 'hostel' or 'library'. Let's test hostel.
        \Route::get('admin/sms/hostel', function () {
            return response('OK');
        })->middleware(['web', 'auth', \App\Http\Middleware\EnsureModuleEnabled::class]);

        $response = $this->get('admin/sms/hostel');

        $response->assertStatus(403);
    }
    
    public function test_disabled_module_by_explicit_name_returns_403()
    {
        $this->actingAs($this->user);

        // Test passing module name explicitly to middleware
        \Route::get('some/custom/route', function () {
            return response('OK');
        })->middleware(['web', 'auth', \App\Http\Middleware\EnsureModuleEnabled::class.':hostel']);

        $response = $this->get('some/custom/route');

        $response->assertStatus(403);
    }
}
