<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class CommunicationConfig extends Model
{
    use BelongsToTenant;
    protected $fillable = ['channel', 'driver', 'config', 'is_active'];

    protected $casts = [
        'config'    => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active config for a channel (sms, email, push).
     */
    public static function activeFor(string $channel): ?self
    {
        return static::where('channel', $channel)->where('is_active', true)->first();
    }

    /**
     * Upsert config for a channel — replaces existing row.
     */
    public static function upsertFor(string $channel, string $driver, array $config, bool $isActive): self
    {
        return static::updateOrCreate(
            ['channel' => $channel],
            ['driver' => $driver, 'config' => $config, 'is_active' => $isActive]
        );
    }
}
