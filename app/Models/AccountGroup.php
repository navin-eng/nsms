<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    protected $fillable = ['name', 'type', 'description'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
