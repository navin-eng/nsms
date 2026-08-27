<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class GradingRule extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'grade_name', 'min_percent', 'max_percent', 
        'grade_point', 'remarks'
    ];
}
