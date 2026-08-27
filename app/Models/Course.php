<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Course extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'duration',
        'semester',
        'requirement',
        'starting_time',
        'closing_time',
        'image',
        'gallery',
        'status',
        'description',
        'fulldescription',
    ];
}
