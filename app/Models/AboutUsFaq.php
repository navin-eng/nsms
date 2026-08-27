<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class AboutUsFaq extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'status',
    ];
}
