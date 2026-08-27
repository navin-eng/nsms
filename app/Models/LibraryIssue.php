<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class LibraryIssue extends Model
{
    use BelongsToTenant;
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
        return system_date($date);
    }
}
