<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class LeaveRequest extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $guarded = ['id'];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function leavable()
    {
        return $this->morphTo();
    }
}
