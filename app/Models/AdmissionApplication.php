<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class AdmissionApplication extends Model
{
    use BelongsToTenant;
    protected $guarded = ['id'];
    
    protected $casts = [
        'dob' => 'date',
        'application_date' => 'date',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function documents()
    {
        return $this->hasMany(AdmissionDocument::class);
    }
}
