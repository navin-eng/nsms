<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryPurchase;
use App\Models\InventoryItem;
use App\Models\InventorySupplier;
use App\Models\InventoryStore;
use Illuminate\Http\Request;

class InventoryPurchaseController extends Controller
{
    public function index()
    {
        $purchases = InventoryPurchase::with(["item", "supplier", "store"])->orderByDesc("purchase_date")->get();
        $items = InventoryItem::orderBy("name")->get();
        $suppliers = InventorySupplier::orderBy("name")->get();
        $stores = InventoryStore::orderBy("name")->get();
        return view("backend.pages.inventory.purchases.index", compact("purchases", "items", "suppliers", "stores"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "inventory_item_id" => "required|exists:inventory_items,id",
            "inventory_supplier_id" => "nullable|exists:inventory_suppliers,id",
            "inventory_store_id" => "nullable|exists:inventory_stores,id",
            "quantity" => "required|integer|min:1",
            "unit_price" => "required|numeric|min:0",
            "total_price" => "required|numeric|min:0",
            "purchase_date" => "required|date",
            "invoice_number" => "nullable|string|max:100",
            "note" => "nullable|string"
        ]);

        $purchase = InventoryPurchase::create($request->all());

        // Update item stock
        $item = InventoryItem::find($request->inventory_item_id);
        $item->increment("current_stock", $request->quantity);

        return redirect()->route("admin.inventory.purchases.index")->with("success", "Purchase added and stock updated.");
    }

    public function destroy(InventoryPurchase $purchase)
    {
        $item = InventoryItem::find($purchase->inventory_item_id);
        if ($item) {
            $item->decrement("current_stock", $purchase->quantity);
        }
        $purchase->delete();
        return redirect()->route("admin.inventory.purchases.index")->with("success", "Purchase deleted and stock reverted.");
    }
}
