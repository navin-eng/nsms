<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class InventoryPurchase extends Model
{
    use BelongsToTenant;
    protected $fillable = ['inventory_item_id', 'inventory_supplier_id', 'inventory_store_id', 'quantity', 'unit_price', 'total_price', 'purchase_date', 'invoice_number', 'attachment', 'note'];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function supplier()
    {
        return $this->belongsTo(InventorySupplier::class, 'inventory_supplier_id');
    }

    public function store()
    {
        return $this->belongsTo(InventoryStore::class, 'inventory_store_id');
    }
}
