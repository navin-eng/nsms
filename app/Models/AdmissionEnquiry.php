<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class AdmissionEnquiry extends Model
{
    use BelongsToTenant;
    protected $guarded = ['id'];
    
    protected $casts = [
        'enquiry_date' => 'date',
    ];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }
}
