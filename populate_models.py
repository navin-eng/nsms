def replace_file(path, content):
    with open(path, "w") as f:
        f.write(content)

replace_file("app/Models/InventoryCategory.php", """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    protected $fillable = ['name', 'type', 'description'];

    public function items()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
""")

replace_file("app/Models/InventoryStore.php", """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStore extends Model
{
    protected $fillable = ['name', 'code', 'description'];
}
""")

replace_file("app/Models/InventorySupplier.php", """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventorySupplier extends Model
{
    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'address'];
}
""")

replace_file("app/Models/InventoryItem.php", """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = ['inventory_category_id', 'name', 'sku_code', 'unit', 'description', 'current_stock'];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }
}
""")

replace_file("app/Models/InventoryPurchase.php", """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryPurchase extends Model
{
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
""")

replace_file("app/Models/InventoryIssue.php", """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryIssue extends Model
{
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
""")

replace_file("app/Models/InventoryMaintenance.php", """<?php
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
""")
