<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Section extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $guarded = [];
    
    // Keep for backward compat where a single class context is needed
    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    // Many-to-many: a section can be shared across multiple classes
    public function academicClasses()
    {
        return $this->belongsToMany(AcademicClass::class, 'academic_class_section');
    }
}
