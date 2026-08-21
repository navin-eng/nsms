<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['account_group_id', 'name', 'code', 'description', 'is_default'];

    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class);
    }
}
