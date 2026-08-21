<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\StaffAccountCreated;

class UserController extends Controller
{
    public function index()
    {
        $staffs = Staff::with(['user.roles', 'department'])->orderBy('first_name')->paginate(20);
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('backend.pages.sms.users.index', compact('staffs', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'email' => 'required|email|unique:users,email',
            'roles' => 'array',
        ]);

        $staff = Staff::findOrFail($request->staff_id);

        if ($staff->user_id) {
            return redirect()->back()->with('error', 'This staff member already has a User ID.');
        }

        // Update staff email if they didn't have one
        if (empty($staff->email)) {
            $staff->email = $request->email;
            $staff->save();
        }

        // Generate Password
        $plainPassword = Str::random(10); // 10 chars, letters, numbers, no symbols
        
        $imagePath = $staff->photo ? 'storage/'.$staff->photo : 'backend/admin/images/avatar.png';

        $user = User::create([
            'name' => $staff->first_name . ' ' . $staff->last_name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'a_type' => 'S',
            'image' => $imagePath
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        // Link to staff
        $staff->user_id = $user->id;
        $staff->save();

        // Send Email
        try {
            Mail::to($user->email)->send(new StaffAccountCreated($user->name, $user->email, $plainPassword));
            $emailMsg = " Credentials emailed to user.";
        } catch (\Exception $e) {
            $emailMsg = " Could not send email. Password is: " . $plainPassword;
        }

        return redirect()->route('sms.users.index')->with('success', 'User ID generated successfully.' . $emailMsg);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'roles' => 'array',
        ]);

        if ($request->has('reset_password') && $request->reset_password == '1') {
            $plainPassword = Str::random(10);
            $user->password = Hash::make($plainPassword);
            $user->save();
            
            try {
                Mail::to($user->email)->send(new StaffAccountCreated($user->name, $user->email, $plainPassword));
                $emailMsg = " New credentials emailed.";
            } catch (\Exception $e) {
                $emailMsg = " Could not send email. New Password is: " . $plainPassword;
            }
            session()->flash('success', 'Password reset successfully.' . $emailMsg);
        } else {
            session()->flash('success', 'User ID roles updated successfully.');
        }

        $user->syncRoles($request->roles ?? []);

        return redirect()->route('sms.users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('sms.users.index')->with('success', 'User ID deleted successfully.');
    }
}
