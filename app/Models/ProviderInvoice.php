<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderInvoice extends Model
{
    use HasFactory;

    protected $connection = 'provider';

    protected $fillable = [
        'school_id',
        'invoice_number',
        'package_name',
        'amount',
        'subtotal',
        'tax_amount',
        'discount',
        'billing_cycle',
        'subscription_start',
        'subscription_end',
        'status',
        'paid_at',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'subscription_start' => 'date',
        'subscription_end' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
