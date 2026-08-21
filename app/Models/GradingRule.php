<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingRule extends Model
{
    protected $fillable = [
        'grade_name', 'min_percent', 'max_percent', 
        'grade_point', 'remarks'
    ];
}
