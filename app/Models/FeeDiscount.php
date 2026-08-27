<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class FeeDiscount extends Model
{
    use BelongsToTenant;
    protected $fillable = ['name', 'type', 'amount', 'description'];
}
