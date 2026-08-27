<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class FeeType extends Model
{
    use BelongsToTenant;
    protected $fillable = ['name', 'description'];
}
