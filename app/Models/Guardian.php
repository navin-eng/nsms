<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Guardian extends Model
{
    use BelongsToTenant;
    protected $guarded = ['id'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
