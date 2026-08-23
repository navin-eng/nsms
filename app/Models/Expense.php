<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_date',
        'reference',
        'vendor_id',
        'expense_account_id',
        'payment_account_id',
        'amount',
        'description',
        'journal_entry_id',
        'status',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function expenseAccount()
    {
        return $this->belongsTo(Account::class, 'expense_account_id');
    }

    public function paymentAccount()
    {
        return $this->belongsTo(Account::class, 'payment_account_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }
}
