<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class LibraryCategory extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $guarded = [];

    public function books()
    {
        return $this->hasMany(LibraryBook::class);
    }
}
