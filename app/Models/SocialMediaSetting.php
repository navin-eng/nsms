<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'is_active',
        'api_key',
        'api_secret',
        'access_token',
        'page_id',
        'custom_message_template',
    ];
}
