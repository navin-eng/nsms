<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Message extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'desc',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
