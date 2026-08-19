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
}
