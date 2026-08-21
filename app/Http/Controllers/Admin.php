<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Token;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class Admin extends Controller
{
    public function login()
    {
        Token::truncate();
        return view('backend.auth.login');
    }
    public function register()
    {
        Token::truncate();
        if (DB::table('users')->count() > 0 && (!Auth::check() || Auth::user()->a_type !== 'A')) {
            return redirect()->route('secure.login')->with('error', 'New users can only be created by the super admin.');
        }
        return view('backend.auth.register');
    }

    // Registering the user
    public function registerAdmin(Request $request)
    {
        Token::truncate();
        $count = DB::table('users')->count();
        if($count ==0)
        {
            $admin = new User();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = Hash::make($request->password);
            $admin->a_type = 'A';
            if($request->hasFile('image'))
            {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $imageName = Str::random(20).rand(0,9999).time().'.'.$extension;
                $image->move('backend/admin/images/',$imageName);
            }
            $admin->image = 'backend/admin/images/'.$imageName;
            $admin->save();
            session()->flash('success','Register Successfully Login again for security reason');
            return redirect('/admin/dashboard/login');
        }
        elseif(Auth::user() && Auth::user()->a_type == 'A')
        {
            $admin = new User();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = Hash::make($request->password);
            $admin->a_type = 'E';
            if($request->hasFile('image'))
            {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $imageName = Str::random(20).rand(0,9999).time().'.'.$extension;
                $image->move('backend/admin/images/',$imageName);
            }
            $admin->image = 'backend/admin/images/'.$imageName;
            $admin->save();
            session()->flash('success','Register Successfully');
            return back();
        }
        else
        {
            return back()->with('error','Owner are only allowed');
        }
    }
    public function adminCheck(Request $request)
    {
        Token::truncate();
        $credentail = $request->only('email','password');
        $remeber = true;
        if (Auth::attempt($credentail,$remeber)) {
            $user = Auth::user();
            if ($user->a_type === 'P') {
                return redirect()->route('parent.dashboard')->with('success','Login Successfully');
            }
            if ($user->a_type === 'ST') {
                return redirect()->route('student.dashboard')->with('success','Login Successfully');
            }
            return redirect('/admin/portal')->with('success','Login Successfully');
        } else {
            return back()->with('error','Email and password doesnot match');
        }

    }

    public function forgotPassword()
    {
        Token::truncate();
        return view('backend.auth.resetemail');
    }

    public function emailCheck(Request $request)
    {
        $checkEmail = User::where('email','=',$request->email)->first();
        if(!$checkEmail)
        {
            return back()->with('error','Email Doesnot Match');
        }
        else
        {
            $otp = rand(12345,99999);
            DB::table('tokens')->insert([
                'email' => $request->email,
                'code' => $otp
            ]);
            $mail_data = [
                'sender' => 'donotreplygplc@gmail.com',
                'reciever' => $request->email,
                'from' => 'Green Peace Lincoln',
                'subject' => 'Forgot Password',
                'body' => $otp,
            ];
            Mail::send('backend.mail.otp',$mail_data, function($message) use ($mail_data){
                $message->to($mail_data['reciever'])->from($mail_data['sender'],$mail_data['from'])->subject($mail_data['subject']);
            });
            return redirect('/admin/dashboard/reset/password')->with('success','You have got OTP code check your email');
        }
    }

    public function resetPassword(Request $request)
    {
        $code = $request->code;
        $codeCheck = Token::where('code','=',$code)->first();
        if ($codeCheck == true) {
            $takeEmail = $codeCheck->email;
            $realEmail = User::where('email','=',$takeEmail)->first();
            $realEmail->password = Hash::make($request->password);
            $realEmail->update();
            return redirect('/admin/dashboard/login')->with('success','Password Reset Successfully Login again for security reason');

        } else {
            return back()->with('error','Verification Code doesnot Match');
        }

    }
    public function profileUpdate(Request $request)
    {
        $admin = User::find($request->id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->password == null) {
        } else {
            $admin->password = Hash::make($request->password);
        }

        if ($admin->a_type == 'A') {
            $admin->a_type = 'A';
        } else {
            $admin->a_type = 'E';
        }
        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20).rand(0,9999).time().'.'.$extension;
            $image->move('backend/admin/images/',$imageName);
            $admin->image = 'backend/admin/images/'.$imageName;
        }
        $admin->update();
        return back()->with('success','Profile Updated');
    }

    public function adminTable()
    {
        $editor = User::where('a_type','=','E')->get();
        return view('backend.pages.admin',compact('editor'));
    }
    public function adminDelete($id)
    {
        $admin = User::find($id);
        $admin->delete();
        return back()->with('success','Editor Deleted');
    }

    public function profile()
    {
        return view('backend.pages.profile');
    }

    public function portal()
    {
        // SMS staff users should go directly to SMS — they have no access to CMS
        if (auth()->user()->a_type === 'S') {
            return redirect()->route('sms.dashboard');
        }

        return view('backend.pages.portal');
    }

    public function smsDashboard()
    {
        return view('backend.pages.sms.dashboard');
    }
}
