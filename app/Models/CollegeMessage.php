<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class CollegeMessage extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'college_messages';

    protected $fillable = ['name', 'designation', 'message', 'image', 'order', 'status'];
}
