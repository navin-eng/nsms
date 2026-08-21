<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventorySupplier;
use Illuminate\Http\Request;

class InventorySupplierController extends Controller
{
    public function index()
    {
        $suppliers = InventorySupplier::orderBy("name")->get();
        return view("backend.pages.inventory.suppliers.index", compact("suppliers"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "contact_person" => "nullable|string|max:255",
            "phone" => "nullable|string|max:50",
            "email" => "nullable|email|max:255",
            "address" => "nullable|string"
        ]);

        InventorySupplier::create($request->all());
        return redirect()->route("admin.inventory.suppliers.index")->with("success", "Supplier added successfully.");
    }

    public function update(Request $request, InventorySupplier $supplier)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "contact_person" => "nullable|string|max:255",
            "phone" => "nullable|string|max:50",
            "email" => "nullable|email|max:255",
            "address" => "nullable|string"
        ]);

        $supplier->update($request->all());
        return redirect()->route("admin.inventory.suppliers.index")->with("success", "Supplier updated successfully.");
    }

    public function destroy(InventorySupplier $supplier)
    {
        $supplier->delete();
        return redirect()->route("admin.inventory.suppliers.index")->with("success", "Supplier deleted successfully.");
    }
}
