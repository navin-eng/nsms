<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\School;
use App\Support\TenantContext;
use Illuminate\Support\Facades\URL;

class ResolvePublicTenant
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
        $slug = $request->route('school_slug');

        if ($slug) {
            $school = School::where('slug', $slug)->first();
            
            if (!$school) {
                abort(404, 'School not found.');
            }
            if (!$school->isOperational()) {
                abort(403, 'This school is currently suspended or inactive.');
            }

            // Set the public school ID so the TenantScope knows which school's data to load
            TenantContext::setPublicSchoolId($school->id);

            // Make the school globally accessible for views
            app()->instance('public_school', $school);

            // Automatically inject the school_slug parameter into all generated route() links
            URL::defaults(['school_slug' => $school->slug]);
        }

        return $next($request);
    }
}
