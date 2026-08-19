<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $guarded = ['id'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
