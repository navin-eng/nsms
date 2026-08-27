<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use App\Support\TenantContext;

trait BelongsToTenant
{
    /**
     * Boot the trait — register the global scope and auto-fill school_id on create.
     */
    protected static function bootBelongsToTenant(): void
    {
        // Auto-apply tenant filter to all queries
        static::addGlobalScope(new TenantScope());

        // Auto-fill school_id when creating a new record
        static::creating(function ($model) {
            if (
                empty($model->school_id) &&
                !app()->runningInConsole() &&
                TenantContext::schoolId()
            ) {
                $model->school_id = TenantContext::schoolId();
            }
        });
    }
}
