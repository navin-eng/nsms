<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class InventoryCategory extends Model
{
    use BelongsToTenant;
    protected $fillable = ['name', 'type', 'description'];

    public function items()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
