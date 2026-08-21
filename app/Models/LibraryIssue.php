<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryIssue extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function bookCopy()
    {
        return $this->belongsTo(LibraryBookCopy::class, 'library_book_copy_id');
    }

    public function borrower()
    {
        return $this->morphTo();
    }
    
    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function getFormattedIssueDateAttribute()
    {
        return $this->formatDate($this->issue_date);
    }

    public function getFormattedDueDateAttribute()
    {
        return $this->formatDate($this->due_date);
    }

    public function getFormattedReturnDateAttribute()
    {
        return $this->formatDate($this->return_date);
    }

    private function formatDate($date)
    {
        if (!$date) return null;
        
        $calendarSystem = SiteSetting::current()->calendar_system ?? 'AD';
        
        if ($calendarSystem === 'BS') {
            try {
                return \Pratiksh\Nepalidate\Services\NepaliDate::create(\Carbon\Carbon::parse($date))->toBS();
            } catch (\Exception $e) {
                return $date->format('M d, Y');
            }
        }
        
        return $date->format('M d, Y');
    }
}
