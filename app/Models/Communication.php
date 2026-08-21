<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'subject',
        'message',
        'recipient_id',
        'recipient_type',
        'status',
        'error_message',
    ];
}
