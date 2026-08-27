<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ActivityLog extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'module',
        'action',
        'user_id',
        'user_name',
        'ip_address',
        'location',
        'user_agent',
        'summary',
        'model_type',
        'model_id',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function getActionColorAttribute()
    {
        return match(strtolower($this->action)) {
            'created', 'create'       => 'success',
            'updated', 'update'       => 'warning',
            'deleted', 'delete',
            'force deleted'           => 'danger',
            'restored'                => 'info',
            default                   => 'secondary',
        };
    }
}
