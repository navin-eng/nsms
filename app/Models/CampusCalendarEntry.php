<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusCalendarEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'entry_type',
        'start_date',
        'end_date',
        'result_link',
        'description',
        'status',
    ];

    public function getEntryTypeLabelAttribute()
    {
        return match ($this->entry_type) {
            'holiday' => 'Holiday',
            'exam' => 'Exam',
            'test' => 'Test',
            'cca_eca' => 'CCA / ECA',
            'result' => 'Result',
            default => 'Other',
        };
    }
}
