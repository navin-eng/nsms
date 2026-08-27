<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class FeeInvoice extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'student_id', 'academic_year_id', 'nepali_month', 'title', 'subtotal', 
        'discount_amount', 'fine_amount', 'total_amount', 'paid_amount', 
        'previous_due', 'remarks', 'status', 'due_date', 'journal_entry_id'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function items()
    {
        return $this->hasMany(FeeInvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }
}
