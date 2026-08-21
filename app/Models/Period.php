<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_break' => 'boolean',
    ];

    public function getStartTimeFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->start_time)->format('h:i A');
    }

    public function getEndTimeFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->end_time)->format('h:i A');
    }

    public function timetableEntries()
    {
        return $this->hasMany(TimetableEntry::class);
    }
}
