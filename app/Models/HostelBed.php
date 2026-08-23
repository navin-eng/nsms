<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelBed extends Model
{
    protected $fillable = ['hostel_room_id', 'bed_number', 'status'];

    public function room()
    {
        return $this->belongsTo(HostelRoom::class, 'hostel_room_id');
    }

    public function activeAllocation()
    {
        return $this->hasOne(HostelAllocation::class)->where('status', 'Active');
    }

    public function allocations()
    {
        return $this->hasMany(HostelAllocation::class);
    }
}
