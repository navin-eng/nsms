<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class FeePayment extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'fee_invoice_id', 'payment_method', 'amount', 'payment_date', 
        'reference_number', 'receipt_number', 'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(FeeInvoice::class, 'fee_invoice_id');
    }
}
