<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntryItem extends Model
{
    protected $fillable = ['journal_entry_id', 'account_id', 'type', 'amount'];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    protected static function booted()
    {
        static::updating(function ($item) {
            $dirty = $item->getDirty();
            $allowed = ['is_reconciled', 'reconciled_at', 'updated_at'];
            
            foreach (array_keys($dirty) as $key) {
                if (!in_array($key, $allowed)) {
                    throw new \Exception('Journal Entry Items are immutable. Only reconciliation status can be updated.');
                }
            }
        });

        static::deleting(function ($item) {
            throw new \Exception('Journal Entry Items are immutable and cannot be deleted.');
        });
    }
}
