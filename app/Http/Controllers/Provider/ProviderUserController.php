<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProviderUser;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ProviderUserController extends Controller
{
    public function index()
    {
        abort_unless(auth('provider')->user()->can('provider_manage_users'), 403, 'Unauthorized access.');

        $users = ProviderUser::with('roles')->paginate(15);
        $roles = Role::where('guard_name', 'provider')->get();

        return view('provider.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        abort_unless(auth('provider')->user()->can('provider_manage_users'), 403, 'Unauthorized access.');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:provider.provider_users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|exists:roles,name',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = ProviderUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => str_replace('Provider ', '', $request->role),
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'Provider user created successfully.');
    }

    public function update(Request $request, $id)
    {
        abort_unless(auth('provider')->user()->can('provider_manage_users'), 403, 'Unauthorized access.');

        $user = ProviderUser::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:provider.provider_users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        // Prevent changing role of the primary super admin
        if ($user->email !== 'subscribe.navin@gmail.com') {
            $user->role = str_replace('Provider ', '', $request->role);
            $user->syncRoles([$request->role]);
        }
        
        $user->phone = $request->phone;
        $user->is_active = $request->has('is_active');
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return back()->with('success', 'Provider user updated successfully.');
    }

    public function destroy($id)
    {
        abort_unless(auth('provider')->user()->can('provider_manage_users'), 403, 'Unauthorized access.');

        $user = ProviderUser::findOrFail($id);
        
        if ($user->id === auth('provider')->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        
        if ($user->email === 'subscribe.navin@gmail.com') {
            return back()->with('error', 'You cannot delete the primary Super Admin account.');
        }
        
        $user->delete();
        
        return back()->with('success', 'Provider user deleted.');
    }
}
