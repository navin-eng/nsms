<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryMaintenance;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryMaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = InventoryMaintenance::with("item")->orderByDesc("date")->get();
        $items = InventoryItem::where("current_stock", ">", 0)->orderBy("name")->get();
        return view("backend.pages.inventory.maintenances.index", compact("maintenances", "items"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "inventory_item_id" => "required|exists:inventory_items,id",
            "quantity" => "required|integer|min:1",
            "type" => "required|in:Damaged,Lost,Maintenance",
            "date" => "required|date",
            "description" => "nullable|string",
            "cost" => "nullable|numeric|min:0",
            "status" => "required|in:Pending,Repaired,Discarded"
        ]);

        $item = InventoryItem::find($request->inventory_item_id);
        if ($item->current_stock < $request->quantity) {
            return redirect()->back()->with("error", "Not enough stock available.");
        }

        InventoryMaintenance::create($request->all());

        // Deduct from current stock
        $item->decrement("current_stock", $request->quantity);

        return redirect()->route("admin.inventory.maintenance.index")->with("success", "Record added successfully.");
    }

    public function update(Request $request, InventoryMaintenance $maintenance)
    {
        $request->validate([
            "cost" => "nullable|numeric|min:0",
            "status" => "required|in:Pending,Repaired,Discarded"
        ]);

        $oldStatus = $maintenance->status;
        $maintenance->update($request->only(["cost", "status"]));

        // If status changes to Repaired, it goes back into stock
        if ($oldStatus !== "Repaired" && $maintenance->status === "Repaired") {
            $item = InventoryItem::find($maintenance->inventory_item_id);
            if ($item) {
                $item->increment("current_stock", $maintenance->quantity);
            }
        } 
        // If status changes FROM Repaired to something else, take it back out of stock
        else if ($oldStatus === "Repaired" && $maintenance->status !== "Repaired") {
            $item = InventoryItem::find($maintenance->inventory_item_id);
            if ($item) {
                $item->decrement("current_stock", $maintenance->quantity);
            }
        }

        return redirect()->route("admin.inventory.maintenance.index")->with("success", "Record updated successfully.");
    }
}
