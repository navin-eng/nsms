<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = ['id'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }
    
    // Helper to get current enrollment
    public function currentEnrollment()
    {
        // Assuming the active academic year is the most recent one or managed by a setting
        // For simplicity, we can just get the latest enrollment based on academic year ID
        return $this->hasOne(Enrollment::class)->latestOfMany('academic_year_id');
    }

    public function homeworkSubmissions()
    {
        return $this->hasMany(HomeworkSubmission::class);
    }
}
