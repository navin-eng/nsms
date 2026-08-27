<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class HostelAttendance extends Model
{
    use BelongsToTenant;
    protected $fillable = ['hostel_allocation_id', 'date', 'status', 'remarks'];

    public function allocation()
    {
        return $this->belongsTo(HostelAllocation::class, 'hostel_allocation_id');
    }
}
