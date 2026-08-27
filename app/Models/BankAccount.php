<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class BankAccount extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'account_id',
        'bank_name',
        'account_name',
        'account_number',
        'branch',
        'ifsc_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
