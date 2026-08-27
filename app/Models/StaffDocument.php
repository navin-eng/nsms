<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class StaffDocument extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = ['staff_id', 'title', 'document_path'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
