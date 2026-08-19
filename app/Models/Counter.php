<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_tag',
        'section_title',
        'section_description',
        'title1',
        'counter1',
        'suffix1',
        'icon1',
        'title2',
        'counter2',
        'suffix2',
        'icon2',
        'title3',
        'counter3',
        'suffix3',
        'icon3',
        'title4',
        'counter4',
        'suffix4',
        'icon4',
    ];
}
