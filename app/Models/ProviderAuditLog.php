<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderAuditLog extends Model
{
    protected $table = 'provider_audit_logs';

    protected $fillable = [
        'provider_user_id',
        'school_id',
        'action',
        'description',
        'payload_before',
        'payload_after',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'payload_before' => 'array',
        'payload_after' => 'array',
    ];

    public function providerUser()
    {
        return $this->belongsTo(ProviderUser::class, 'provider_user_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public static function log(string $action, ?School $school = null, ?string $description = null, ?array $before = null, ?array $after = null): self
    {
        return self::create([
            'provider_user_id' => auth('provider')->id(),
            'school_id' => $school ? $school->id : null,
            'action' => $action,
            'description' => $description,
            'payload_before' => $before,
            'payload_after' => $after,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
