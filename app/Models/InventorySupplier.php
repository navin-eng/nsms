<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class InventorySupplier extends Model
{
    use BelongsToTenant;
    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'address'];
}
