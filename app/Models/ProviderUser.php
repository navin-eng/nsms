<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProviderUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'provider_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function auditLogs()
    {
        return $this->hasMany(ProviderAuditLog::class, 'provider_user_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'Super Admin';
    }
}
