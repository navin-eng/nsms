<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Designation extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
