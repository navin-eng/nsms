<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class AccountGroup extends Model
{
    use BelongsToTenant;
    protected $fillable = ['name', 'type', 'description'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
