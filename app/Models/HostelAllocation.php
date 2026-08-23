<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelAllocation extends Model
{
    protected $fillable = ['student_id', 'hostel_bed_id', 'start_date', 'end_date', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function bed()
    {
        return $this->belongsTo(HostelBed::class, 'hostel_bed_id');
    }

    public function room()
    {
        return $this->hasOneThrough(HostelRoom::class, HostelBed::class, 'id', 'id', 'hostel_bed_id', 'hostel_room_id');
    }

    public function attendances()
    {
        return $this->hasMany(HostelAttendance::class);
    }
}
