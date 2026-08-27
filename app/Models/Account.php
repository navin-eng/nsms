<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Account extends Model
{
    use BelongsToTenant;
    protected $fillable = ['account_group_id', 'name', 'code', 'description', 'is_default'];

    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class);
    }
}
