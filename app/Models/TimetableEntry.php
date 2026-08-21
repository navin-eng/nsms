<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    protected $guarded = ['id'];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function getEffectiveStartTimeAttribute()
    {
        return $this->custom_start_time ?? $this->period->start_time;
    }

    public function getEffectiveEndTimeAttribute()
    {
        return $this->custom_end_time ?? $this->period->end_time;
    }
}
