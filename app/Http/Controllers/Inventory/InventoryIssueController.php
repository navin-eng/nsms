<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryIssue;
use App\Models\InventoryItem;
use App\Models\Staff;
use App\Models\Department;
use Illuminate\Http\Request;

class InventoryIssueController extends Controller
{
    public function index()
    {
        $issues = InventoryIssue::with(["item", "issueTo"])->orderByDesc("issue_date")->get();
        $items = InventoryItem::where("current_stock", ">", 0)->orderBy("name")->get();
        $staffs = Staff::orderBy("first_name")->get();
        $departments = Department::orderBy("name")->get();
        
        return view("backend.pages.inventory.issues.index", compact("issues", "items", "staffs", "departments"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "inventory_item_id" => "required|exists:inventory_items,id",
            "issue_to_type" => "required|in:App\Models\Staff,App\Models\Department",
            "issue_to_id" => "required|integer",
            "quantity" => "required|integer|min:1",
            "issue_date" => "required|date",
            "due_date" => "nullable|date",
            "note" => "nullable|string"
        ]);

        $item = InventoryItem::find($request->inventory_item_id);
        if ($item->current_stock < $request->quantity) {
            return redirect()->back()->with("error", "Not enough stock available.");
        }

        InventoryIssue::create($request->all());

        // Update stock
        $item->decrement("current_stock", $request->quantity);

        return redirect()->route("admin.inventory.issues.index")->with("success", "Asset issued successfully.");
    }

    public function returnItem(Request $request, InventoryIssue $issue)
    {
        if ($issue->status === "Returned") {
            return redirect()->back()->with("error", "Asset already returned.");
        }

        $issue->update([
            "status" => "Returned",
            "return_date" => date("Y-m-d")
        ]);

        // Revert stock
        $item = InventoryItem::find($issue->inventory_item_id);
        if ($item) {
            $item->increment("current_stock", $issue->quantity);
        }

        return redirect()->route("admin.inventory.issues.index")->with("success", "Asset returned successfully.");
    }
}
