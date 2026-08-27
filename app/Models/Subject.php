<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Subject extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $guarded = [];

    public function classes()
    {
        return $this->belongsToMany(AcademicClass::class, 'academic_class_subject');
    }
}
