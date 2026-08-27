<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class HomeSection extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'is_visible',
        'sort_order',
    ];
}
