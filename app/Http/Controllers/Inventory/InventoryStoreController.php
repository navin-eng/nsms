<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryStore;
use Illuminate\Http\Request;

class InventoryStoreController extends Controller
{
    public function index()
    {
        $stores = InventoryStore::orderBy('name')->get();
        return view('backend.pages.inventory.stores.index', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'description' => 'nullable|string'
        ]);

        InventoryStore::create($request->all());

        return redirect()->route('admin.inventory.stores.index')->with('success', 'Store added successfully.');
    }

    public function update(Request $request, InventoryStore $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'description' => 'nullable|string'
        ]);

        $store->update($request->all());

        return redirect()->route('admin.inventory.stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(InventoryStore $store)
    {
        $store->delete();
        return redirect()->route('admin.inventory.stores.index')->with('success', 'Store deleted successfully.');
    }
}
