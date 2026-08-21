<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionEnquiry extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'enquiry_date' => 'date',
    ];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }
}
