<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['title', 'status'];

    public function results()
    {
        return $this->hasMany(ExamResult::class, 'exam_id');
    }
    use HasFactory;
}
