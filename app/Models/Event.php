<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'event_type',
        'visit_date',
        'venue',
        'result_link',
        'description',
        'image',
        'gallery',
        'status',
    ];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function getVisitDateAttribute($value)
    {
        return date("d-M-Y",strtotime($value));
    }

    public function getEventTypeLabelAttribute()
    {
        return match ($this->event_type) {
            'holiday' => 'Holiday',
            'exam' => 'Exam',
            'test' => 'Test',
            'cca_eca' => 'CCA / ECA',
            'result' => 'Result',
            default => 'Event',
        };
    }
}
