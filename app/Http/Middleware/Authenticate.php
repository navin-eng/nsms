<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if ($request->is('accounting') || $request->is('accounting/*')) {
                return route('accounting.login');
            }
            // For admin/sms it probably goes to secure.login
            return route('secure.login');
        }
    }
}
