<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            
            // If it's a parent trying to access an admin route, redirect them to the parent portal
            if ($user->a_type === 'P' && $request->is('admin/*')) {
                return redirect()->route('parent.dashboard');
            }
            
            // If it's a student trying to access an admin route, redirect them to the student portal
            if ($user->a_type === 'ST' && $request->is('admin/*')) {
                return redirect()->route('student.dashboard');
            }

            return $next($request);
        }
        else
        {
            return redirect('/admin/dashboard/login')->with('error','unauthorized person are not allowed to visit admin panel');
        }
    }
}
