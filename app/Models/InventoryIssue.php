<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class InventoryIssue extends Model
{
    use BelongsToTenant;
    protected $fillable = ['inventory_item_id', 'issue_to_type', 'issue_to_id', 'quantity', 'issue_date', 'due_date', 'return_date', 'status', 'note'];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function issueTo()
    {
        return $this->morphTo();
    }
}
