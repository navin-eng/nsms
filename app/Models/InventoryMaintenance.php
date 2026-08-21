<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMaintenance extends Model
{
    protected $fillable = ['inventory_item_id', 'quantity', 'type', 'date', 'description', 'cost', 'status'];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
