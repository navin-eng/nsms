<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    protected $fillable = ['name', 'type', 'address', 'warden_id', 'warden_name', 'description'];

    public function warden()
    {
        return $this->belongsTo(Staff::class, 'warden_id');
    }

    public function rooms()
    {
        return $this->hasMany(HostelRoom::class);
    }

    public function allocations()
    {
        return $this->hasManyThrough(HostelAllocation::class, HostelRoom::class, 'hostel_id', 'hostel_bed_id', 'id', 'id');
    }
}
