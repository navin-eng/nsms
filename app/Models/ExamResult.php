<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'student_name', 'symbol_number', 'marks_data'];

    protected $casts = [
        'marks_data' => 'array',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
