<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;

class InventoryCategoryController extends Controller
{
    public function index()
    {
        $categories = InventoryCategory::orderBy('name')->get();
        return view('backend.pages.inventory.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name',
            'type' => 'required|string|in:Consumable,Fixed Asset',
            'description' => 'nullable|string'
        ]);

        InventoryCategory::create($request->all());

        return redirect()->route('admin.inventory.categories.index')->with('success', 'Category added successfully.');
    }

    public function update(Request $request, InventoryCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name,' . $category->id,
            'type' => 'required|string|in:Consumable,Fixed Asset',
            'description' => 'nullable|string'
        ]);

        $category->update($request->all());

        return redirect()->route('admin.inventory.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(InventoryCategory $category)
    {
        if ($category->items()->count() > 0) {
            return redirect()->route('admin.inventory.categories.index')->with('error', 'Cannot delete category because it has associated items.');
        }
        
        $category->delete();
        return redirect()->route('admin.inventory.categories.index')->with('success', 'Category deleted successfully.');
    }
}
