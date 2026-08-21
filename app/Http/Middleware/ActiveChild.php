<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActiveChild
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
        $user = \Illuminate\Support\Facades\Auth::user();
        
        if (!$user || !$user->hasRole('Parent')) {
            return redirect('/');
        }

        $guardian = \App\Models\Guardian::where('user_id', $user->id)->first();
        if (!$guardian) {
            return redirect('/');
        }

        $children = \App\Models\Student::where('guardian_id', $guardian->id)->get();
        if ($children->isEmpty()) {
            return response('No children enrolled in the system.', 403);
        }

        // If no child is selected in session, select the first one
        if (!session()->has('active_child_id')) {
            session(['active_child_id' => $children->first()->id]);
        }

        // Ensure the selected child actually belongs to this guardian
        $activeChildId = session('active_child_id');
        if (!$children->contains('id', $activeChildId)) {
            session(['active_child_id' => $children->first()->id]);
        }

        return $next($request);
    }
}
