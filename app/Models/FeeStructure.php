<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = ['academic_year_id', 'academic_class_id', 'fee_type_id', 'billing_cycle', 'amount'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
}
