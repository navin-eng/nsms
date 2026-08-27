<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class AdmissionDocument extends Model
{
    use BelongsToTenant;
    protected $guarded = ['id'];

    public function application()
    {
        return $this->belongsTo(AdmissionApplication::class, 'admission_application_id');
    }
}
