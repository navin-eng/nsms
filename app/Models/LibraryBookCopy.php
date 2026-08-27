<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class LibraryBookCopy extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $guarded = [];

    public function book()
    {
        return $this->belongsTo(LibraryBook::class, 'library_book_id');
    }

    public function issues()
    {
        return $this->hasMany(LibraryIssue::class);
    }

    public function activeIssue()
    {
        return $this->hasOne(LibraryIssue::class)->where('status', 'issued')->latestOfMany();
    }
}
