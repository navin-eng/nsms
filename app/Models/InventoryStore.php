<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class InventoryStore extends Model
{
    use BelongsToTenant;
    protected $fillable = ['name', 'code', 'description'];
}
