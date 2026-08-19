<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'department_id',
        'designation_id',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'date_of_joining',
        'email',
        'phone',
        'emergency_contact',
        'marital_status',
        'photo',
        'resume',
        'current_address',
        'permanent_address',
        'qualification',
        'experience',
        'basic_salary',
        'contract_type',
        'status',
        'show_on_website',
        'user_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function documents()
    {
        return $this->hasMany(StaffDocument::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
