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

        $schools = $query->latest()->paginate(15)->withQueryString();

        $schoolIds = $schools->pluck('id')->toArray();
        if (!empty($schoolIds)) {
            $studentsCounts = \DB::table('students')->whereIn('school_id', $schoolIds)
                ->select('school_id', \DB::raw('count(*) as aggregate'))
                ->groupBy('school_id')->pluck('aggregate', 'school_id');
            $staffCounts = \DB::table('staff')->whereIn('school_id', $schoolIds)
                ->select('school_id', \DB::raw('count(*) as aggregate'))
                ->groupBy('school_id')->pluck('aggregate', 'school_id');
            $usersCounts = \DB::table('users')->whereIn('school_id', $schoolIds)
                ->select('school_id', \DB::raw('count(*) as aggregate'))
                ->groupBy('school_id')->pluck('aggregate', 'school_id');
                
            foreach ($schools as $school) {
                $school->setAttribute('students_count', $studentsCounts->get($school->id, 0));
                $school->setAttribute('staff_count', $staffCounts->get($school->id, 0));
                $school->setAttribute('users_count', $usersCounts->get($school->id, 0));
            }
        }

        return view('provider.schools.index', compact('schools'));
    }

    /**
     * Show School Onboarding Wizard
     */
    public function create()
    {
        abort_unless(auth('provider')->user()->can('provider_manage_schools'), 403, 'Unauthorized access.');

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
        abort_unless(auth('provider')->user()->can('provider_manage_schools'), 403, 'Unauthorized access.');

        // Assemble full admin email with @nsms.com suffix
        $prefix = strtolower(trim($request->admin_email_prefix ?? $request->admin_email));
        // Remove any @nsms.com if typed in prefix
        $prefix = str_replace('@nsms.com', '', $prefix);
        $prefix = preg_replace('/[^a-z0-9._-]/', '', $prefix);
        $fullAdminEmail = $prefix . '@nsms.com';

        // Check uniqueness of constructed email
        if (User::where('email', $fullAdminEmail)->exists()) {
            return back()->withErrors(['admin_email_prefix' => "The login email '{$fullAdminEmail}' is already taken. Please choose a different prefix."])->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'school_code' => 'required|string|max:32|unique:provider.schools,school_code',
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
            'email' => $fullAdminEmail,
            'username' => $prefix,
            'password' => Hash::make($request->admin_password),
            'a_type' => 'A',
            'image' => 'default.png',
        ]);

        ProviderAuditLog::log(
            'school.created',
            $school,
            "Onboarded new school '{$school->name}' ({$school->school_code}) and created Super Admin {$admin->email}.",
            null,
            $school->toArray()
        );

        return redirect()->route('provider.schools.show', $school->id)
            ->with('success', "School '{$school->name}' onboarded successfully with School Code: {$school->school_code}!")
            ->with('new_password', $request->admin_password);
    }

    /**
     * School Overview & God-Mode Config
     */
    public function show($id)
    {
        $school = School::findOrFail($id);

        // Query super admin from tenant (default) DB — users table doesn't exist on provider connection
        $superAdmin = \DB::table('users')
            ->where('school_id', $school->id)
            ->where(function($query) {
                $query->where('a_type', 'A')->orWhere('role', 'Super Admin');
            })
            ->first();

        $allModules = School::allModules();
        $auditLogs = ProviderAuditLog::where('school_id', $school->id)->with('providerUser')->latest()->take(10)->get();

        // Manual counts from tenant (default) DB — cross-DB withCount() doesn't work with SQLite
        $studentsCount = \DB::table('students')->where('school_id', $school->id)->count();
        $staffCount    = \DB::table('staff')->where('school_id', $school->id)->count();
        $usersCount    = \DB::table('users')->where('school_id', $school->id)->count();
        $classesCount  = \DB::table('academic_classes')->where('school_id', $school->id)->count();

        return view('provider.schools.show', compact(
            'school', 'superAdmin', 'allModules', 'auditLogs',
            'studentsCount', 'staffCount', 'usersCount', 'classesCount'
        ));
    }

    /**
     * Update School Status (Activate, Suspend, Disable, etc.)
     */
    public function updateStatus(Request $request, $id)
    {
        abort_unless(auth('provider')->user()->can('provider_support_tools') || auth('provider')->user()->can('provider_manage_billing'), 403, 'Unauthorized access.');

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
        abort_unless(auth('provider')->user()->can('provider_manage_modules'), 403, 'Unauthorized access.');

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

    /**
     * Reset School Super Admin Password & Optionally Email
     */
    public function resetSchoolAdminPassword(Request $request, $id)
    {
        abort_unless(auth('provider')->user()->can('provider_support_tools'), 403, 'Unauthorized access.');

        $request->validate([
            'new_password' => 'nullable|string|min:6',
            'auto_generate' => 'nullable|boolean',
            'send_email' => 'nullable|boolean',
            'recipient_email' => 'nullable|email',
        ]);

        $school = School::findOrFail($id);
        $admin = User::where('school_id', $school->id)
            ->where(function($q) {
                $q->where('a_type', 'A')->orWhere('role', 'Super Admin');
            })->first();

        if (!$admin) {
            return back()->with('error', "No Super Admin account found for {$school->name}.");
        }

        // Determine password
        $passwordToSet = $request->new_password;
        if ($request->boolean('auto_generate') || empty($passwordToSet)) {
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
            $passwordToSet = 'Nsms@';
            for ($i = 0; $i < 6; $i++) {
                $passwordToSet .= $chars[rand(0, strlen($chars) - 1)];
            }
        }

        $admin->password = Hash::make($passwordToSet);
        $admin->save();

        // Optional Email Dispatch
        $emailSent = false;
        $targetEmail = $request->filled('recipient_email') ? $request->recipient_email : ($school->contact_email ?? $admin->email);

        if ($request->boolean('send_email') && $targetEmail) {
            try {
                $mailData = [
                    'school_name' => $school->name,
                    'school_code' => $school->school_code,
                    'login_url' => route('admin.login'),
                    'username' => $admin->email,
                    'new_password' => $passwordToSet,
                ];

                \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($targetEmail, $mailData) {
                    $message->to($targetEmail)
                        ->subject("Security Notice: Portal Password Reset for {$mailData['school_name']}")
                        ->html("
                            <div style='font-family: sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;'>
                                <h3 style='color: #005f1a;'>NSMS Cloud — School Password Reset</h3>
                                <p>Dear Administration of <strong>{$mailData['school_name']}</strong>,</p>
                                <p>Your School Super Admin account password has been updated by the SaaS Provider God Mode.</p>
                                <div style='background: #f8fafc; padding: 15px; border-radius: 6px; margin: 15px 0; border: 1px solid #cbd5e1;'>
                                    <p style='margin: 4px 0;'><strong>School Code:</strong> <code>{$mailData['school_code']}</code></p>
                                    <p style='margin: 4px 0;'><strong>Login Username:</strong> <code>{$mailData['username']}</code></p>
                                    <p style='margin: 4px 0;'><strong>New Temporary Password:</strong> <code style='font-size: 16px; font-weight: bold; color: #10b981;'>{$mailData['new_password']}</code></p>
                                </div>
                                <p><a href='{$mailData['login_url']}' style='background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-block;'>Log In to School Portal</a></p>
                                <p style='color: #64748b; font-size: 12px;'>For security, please change this password after signing in.</p>
                            </div>
                        ");
                });
                $emailSent = true;
            } catch (\Exception $e) {
                // Ignore mail transport failure in local environment but log
            }
        }

        ProviderAuditLog::log(
            'school.password_reset',
            $school,
            "Reset password for school admin ({$admin->email}). " . ($emailSent ? "Dispatched email to {$targetEmail}." : "Manual handover."),
            null,
            ['admin_email' => $admin->email]
        );

        $msg = "Password for {$admin->email} has been updated to: [ {$passwordToSet} ].";
        if ($emailSent) {
            $msg .= " Sent security notification email to {$targetEmail}.";
        }

        return back()->with('success', $msg)->with('new_password', $passwordToSet);
    }

    /**
     * Edit School details
     */
    public function edit($id)
    {
        abort_unless(auth('provider')->user()->can('provider_manage_schools'), 403, 'Unauthorized access.');
        $school = School::findOrFail($id);
        $nepalLocations = \App\Helpers\NepalLocations::getHierarchy();
        return view('provider.schools.edit', compact('school', 'nepalLocations'));
    }

    /**
     * Update School details
     */
    public function update(Request $request, $id)
    {
        abort_unless(auth('provider')->user()->can('provider_manage_schools'), 403, 'Unauthorized access.');

        $school = School::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:30',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'municipality' => 'nullable|string|max:150',
            'ward_no' => 'nullable|string|max:10',
            'street_address' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255',
        ]);

        $addressParts = array_filter([
            $request->street_address ? trim($request->street_address) : null,
            $request->ward_no ? 'Ward No. ' . trim($request->ward_no) : null,
            $request->municipality,
            $request->district,
            $request->province,
        ]);
        $compiledAddress = !empty($addressParts) ? implode(', ', $addressParts) : ($request->address ?? $school->address);

        $school->update([
            'name' => $request->name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'address' => $compiledAddress,
            'domain' => $request->domain,
        ]);

        ProviderAuditLog::log(
            'school.updated',
            $school,
            "Updated basic details for school '{$school->name}'.",
            null,
            $school->toArray()
        );

        return redirect()->route('provider.schools.show', $school->id)->with('success', 'School details updated successfully.');
    }

    /**
     * Renew/Upgrade Package
     */
    public function renewPackage(Request $request, $id)
    {
        abort_unless(auth('provider')->user()->can('provider_manage_billing'), 403, 'Unauthorized access.');

        $school = School::findOrFail($id);

        $request->validate([
            'package_name' => 'required|string|in:Basic,Professional,Enterprise,Custom',
            'subscription_end' => 'required|date',
            'billing_amount' => 'nullable|numeric|min:0',
        ]);

        $oldPackage = $school->package_name;
        $oldEnd = $school->subscription_end;

        $school->package_name = $request->package_name;
        $school->subscription_end = $request->subscription_end;
        
        // If renewing an expired school, set status to active
        if (in_array($school->status, ['expired', 'suspended']) && now()->lt(\Carbon\Carbon::parse($request->subscription_end))) {
            $school->status = 'active';
        }
        $school->save();

        // Calculate Tax
        $baseAmount = $request->billing_amount ?: 0;
        $taxType = \App\Models\ProviderSetting::get('tax_type', 'exclusive');
        $taxRate = (float) \App\Models\ProviderSetting::get('tax_rate', 13);
        
        $subtotal = $baseAmount;
        $taxAmount = 0;
        $totalAmount = $baseAmount;

        if ($baseAmount > 0 && $taxType !== 'none') {
            if ($taxType === 'exclusive') {
                $taxAmount = $subtotal * ($taxRate / 100);
                $totalAmount = $subtotal + $taxAmount;
            } elseif ($taxType === 'inclusive') {
                $subtotal = $totalAmount / (1 + ($taxRate / 100));
                $taxAmount = $totalAmount - $subtotal;
            }
        }

        // Create an invoice
        $invoiceNumber = 'INV-' . strtoupper(Str::random(6)) . '-' . date('Y');
        
        \App\Models\ProviderInvoice::create([
            'school_id' => $school->id,
            'invoice_number' => $invoiceNumber,
            'package_name' => $school->package_name,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount' => 0,
            'amount' => $totalAmount,
            'billing_cycle' => 'yearly',
            'subscription_start' => now(),
            'subscription_end' => $school->subscription_end,
            'status' => 'pending',
        ]);

        ProviderAuditLog::log(
            'school.renewed',
            $school,
            "Renewed/Upgraded package for '{$school->name}' from {$oldPackage} to {$school->package_name}. Expiry extended to {$school->subscription_end}. Generated Invoice {$invoiceNumber}.",
            ['package' => $oldPackage, 'end' => $oldEnd],
            ['package' => $school->package_name, 'end' => $school->subscription_end]
        );

        return back()->with('success', "Subscription updated. The school is now on {$school->package_name} until " . \Carbon\Carbon::parse($school->subscription_end)->format('M d, Y') . ". Invoice generated.");
    }
}
