<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Schema;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * Automatically restrict all queries to the current school's data.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Skip scoping in CLI (artisan commands, migrations, seeders)
        if (app()->runningInConsole()) {
            return;
        }

        // Skip if provider (God Mode) is authenticated — they see all schools
        if (Auth::guard('provider')->check()) {
            return;
        }

        if (!Schema::hasColumn($model->getTable(), 'school_id')) {
            return;
        }

        // Apply tenant scope for school users across the web and accounting guards.
        if (TenantContext::schoolId()) {
            $builder->where($model->getTable() . '.school_id', TenantContext::schoolId());
        }
    }
}
