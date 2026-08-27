<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Page extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'status'];
}
