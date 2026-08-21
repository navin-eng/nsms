<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventorySupplier extends Model
{
    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'address'];
}
