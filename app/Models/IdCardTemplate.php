<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdCardTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'layout',
        'html_content',
        'css_content',
        'background_image',
        'is_default',
    ];
}
