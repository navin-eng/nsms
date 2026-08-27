<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class InventoryItem extends Model
{
    use BelongsToTenant;
    protected $fillable = ['inventory_category_id', 'name', 'sku_code', 'unit', 'description', 'current_stock'];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }
}
