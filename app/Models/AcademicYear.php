<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class AcademicYear extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $guarded = [];
}
