<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
