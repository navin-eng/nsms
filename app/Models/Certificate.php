<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use App\Services\DocumentVerificationService;

class Certificate extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'issue_date' => 'date',
        'metadata'   => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            if (empty($certificate->qr_token)) {
                $certificate->qr_token = DocumentVerificationService::generateToken('cert');
            }
            if (empty($certificate->certificate_no)) {
                $year = date('Y');
                $count = static::whereYear('created_at', $year)->count() + 1;
                $certificate->certificate_no = sprintf('CERT-%s-%04d', $year, $count);
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function getVerificationUrlAttribute()
    {
        return DocumentVerificationService::getVerificationUrl($this->qr_token);
    }
}
