<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;
use App\Models\ProviderAuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SchoolManagementController extends Controller
{
    /**
     * Provider Dashboard (Platform-Wide God-Mode Metrics)
     */
    public function dashboard()
    {
        $totalSchools = School::count();
        $activeSchools = School::where('status', 'active')->count();
        $trialSchools = School::where('status', 'trial')->count();
        $suspendedSchools = School::where('status', 'suspended')->count();
        $disabledSchools = School::where('status', 'disabled')->count();

        $totalStudents = Student::count();
        $totalStaff = Staff::count();

        $recentSchools = School::latest()->take(6)->get();
        $recentLogs = ProviderAuditLog::with(['providerUser', 'school'])->latest()->take(8)->get();

        return view('provider.dashboard', compact(
            'totalSchools',
            'activeSchools',
            'trialSchools',
            'suspendedSchools',
            'disabledSchools',
            'totalStudents',
            'totalStaff',
            'recentSchools',
            'recentLogs'
        ));
    }

    /**
     * Schools List with Filtering & Search
     */
    public function index(Request $request)
    {
        $query = School::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('school_code', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('package')) {
            $query->where('package_name', $request->package);
        }

        $schools = $query->withCount(['students', 'staff', 'users'])->latest()->paginate(15)->withQueryString();

        return view('provider.schools.index', compact('schools'));
    }

    /**
     * Show School Onboarding Wizard
     */
    public function create()
    {
        $generatedCode = School::generateUniqueCode();
        $allModules = School::allModules();
        $nepalLocations = \App\Helpers\NepalLocations::getHierarchy();

        return view('provider.schools.create', compact('generatedCode', 'allModules', 'nepalLocations'));
    }

    /**
     * Store New School & Provision Super Admin Account
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_code' => 'required|string|max:32|unique:schools,school_code',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:30',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'municipality' => 'nullable|string|max:150',
            'ward_no' => 'nullable|string|max:10',
            'street_address' => 'nullable|string|max:255',
            'package_name' => 'required|string|in:Basic,Professional,Enterprise,Custom',
            'status' => 'required|string|in:pending,trial,active,suspended,disabled',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:6',
            'modules' => 'nullable|array',
        ]);

        $slug = Str::slug($request->name);
        if (School::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . strtolower(Str::random(4));
        }

        // Build clean structured address
        $addressParts = array_filter([
            $request->street_address ? trim($request->street_address) : null,
            $request->ward_no ? 'Ward No. ' . trim($request->ward_no) : null,
            $request->municipality,
            $request->district,
            $request->province,
        ]);
        $compiledAddress = !empty($addressParts) ? implode(', ', $addressParts) : ($request->address ?? null);

        // 1. Create School Tenant
        $school = School::create([
            'name' => $request->name,
            'school_code' => strtoupper($request->school_code),
            'slug' => $slug,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'address' => $compiledAddress,
            'status' => $request->status,
            'package_name' => $request->package_name,
            'subscription_start' => now(),
            'subscription_end' => now()->addYear(),
            'enabled_modules' => $request->modules ?? array_keys(School::allModules()),
            'feature_flags' => [
                'nepali_bikram_sambat' => true,
                'double_entry_accounting' => true,
                'sms_gateway' => true,
            ],
            'settings' => [
                'currency' => 'NPR',
                'academic_calendar' => 'BS',
            ],
            'admin_notes' => $request->admin_notes,
        ]);

        // 2. Create School Super Admin User
        $admin = User::create([
            'school_id' => $school->id,
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'username' => 'admin',
            'password' => Hash::make($request->admin_password),
            'a_type' => 'A',
        ]);

        ProviderAuditLog::log(
            'school.created',
            $school,
            "Onboarded new school '{$school->name}' ({$school->school_code}) and created Super Admin {$admin->email}.",
            null,
            $school->toArray()
        );

        return redirect()->route('provider.schools.show', $school->id)
            ->with('success', "School '{$school->name}' onboarded successfully with School Code: {$school->school_code}!");
    }

    /**
     * School Overview & God-Mode Config
     */
    public function show($id)
    {
        $school = School::with(['users' => function($q) {
            $q->where('a_type', 'A')->orWhere('role', 'Super Admin');
        }])->withCount(['students', 'staff', 'users'])->findOrFail($id);

        $allModules = School::allModules();
        $auditLogs = ProviderAuditLog::where('school_id', $school->id)->with('providerUser')->latest()->take(10)->get();

        return view('provider.schools.show', compact('school', 'allModules', 'auditLogs'));
    }

    /**
     * Update School Status (Activate, Suspend, Disable, etc.)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,trial,active,suspended,disabled,expired,archived',
            'reason' => 'nullable|string|max:500',
        ]);

        $school = School::findOrFail($id);
        $oldStatus = $school->status;
        $school->status = $request->status;
        $school->save();

        ProviderAuditLog::log(
            'school.status_changed',
            $school,
            "Changed status of '{$school->name}' from {$oldStatus} to {$school->status}." . ($request->reason ? " Reason: {$request->reason}" : ''),
            ['status' => $oldStatus],
            ['status' => $school->status]
        );

        return back()->with('success', "School status updated to " . ucfirst($school->status));
    }

    /**
     * Update Module Entitlements
     */
    public function updateModules(Request $request, $id)
    {
        $school = School::findOrFail($id);
        $oldModules = $school->enabled_modules;
        
        $school->enabled_modules = $request->input('modules', []);
        $school->save();

        ProviderAuditLog::log(
            'school.modules_updated',
            $school,
            "Updated module entitlements for '{$school->name}'.",
            ['modules' => $oldModules],
            ['modules' => $school->enabled_modules]
        );

        return back()->with('success', "School module entitlements updated successfully.");
    }
}
