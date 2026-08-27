<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ExamSchedule extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'exam_id', 'academic_class_id', 'subject_id', 
        'exam_date', 'start_time', 'end_time', 
        'theory_full_marks', 'theory_pass_marks', 
        'practical_full_marks', 'practical_pass_marks'
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
