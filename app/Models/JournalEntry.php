<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = ['entry_date', 'reference', 'description'];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    protected static function booted()
    {
        static::updating(function ($entry) {
            throw new \Exception('Journal Entries are immutable and cannot be updated. Please post a reversing Contra-Entry instead.');
        });

        static::deleting(function ($entry) {
            throw new \Exception('Journal Entries are immutable and cannot be deleted. Please post a reversing Contra-Entry instead.');
        });
    }
}
