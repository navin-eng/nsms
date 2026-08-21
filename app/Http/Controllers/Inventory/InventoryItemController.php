<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index()
    {
        $items = InventoryItem::with("category")->orderBy("name")->get();
        $categories = InventoryCategory::orderBy("name")->get();
        return view("backend.pages.inventory.items.index", compact("items", "categories"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "inventory_category_id" => "required|exists:inventory_categories,id",
            "name" => "required|string|max:255",
            "sku_code" => "nullable|string|max:100",
            "unit" => "required|string|max:50",
            "description" => "nullable|string"
        ]);

        InventoryItem::create($request->all());
        return redirect()->route("admin.inventory.items.index")->with("success", "Item added successfully.");
    }

    public function update(Request $request, InventoryItem $item)
    {
        $request->validate([
            "inventory_category_id" => "required|exists:inventory_categories,id",
            "name" => "required|string|max:255",
            "sku_code" => "nullable|string|max:100",
            "unit" => "required|string|max:50",
            "description" => "nullable|string"
        ]);

        $item->update($request->all());
        return redirect()->route("admin.inventory.items.index")->with("success", "Item updated successfully.");
    }

    public function destroy(InventoryItem $item)
    {
        $item->delete();
        return redirect()->route("admin.inventory.items.index")->with("success", "Item deleted successfully.");
    }
}
