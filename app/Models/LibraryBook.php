<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(LibraryCategory::class, 'library_category_id');
    }

    public function copies()
    {
        return $this->hasMany(LibraryBookCopy::class);
    }
}
