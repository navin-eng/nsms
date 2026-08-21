<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicClass extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'academic_class_section');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'academic_class_subject');
    }
}
