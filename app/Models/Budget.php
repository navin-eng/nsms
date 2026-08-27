<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Budget extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'account_id',
        'fiscal_year',
        'start_date',
        'end_date',
        'amount',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
