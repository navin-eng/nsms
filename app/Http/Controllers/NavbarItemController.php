<?php

namespace App\Http\Controllers;

use App\Models\NavbarItem;
use App\Models\Page;
use Illuminate\Http\Request;

class NavbarItemController extends Controller
{
    public function index()
    {
        $items = NavbarItem::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $pages = Page::where('status', 1)->get();
        return view('backend.pages.navbar.index', compact('items', 'pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:route,page,custom,dynamic_courses',
            'value' => 'nullable|string',
            'css_class' => 'nullable|string',
        ]);

        $order = NavbarItem::where('parent_id', $request->parent_id)->max('order') + 1;

        NavbarItem::create([
            'title' => $request->title,
            'type' => $request->type,
            'value' => $request->value,
            'css_class' => $request->css_class,
            'parent_id' => $request->parent_id,
            'order' => $order,
        ]);

        return back()->with('success', 'Navbar item added successfully.');
    }

    public function destroy($id)
    {
        $item = NavbarItem::findOrFail($id);
        $item->delete();
        return back()->with('success', 'Navbar item deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $orderData = json_decode($request->order_data, true);
        if (!$orderData) return back()->with('error', 'Invalid order data.');

        $this->updateOrder($orderData, null);

        return back()->with('success', 'Navbar order updated.');
    }

    private function updateOrder($items, $parentId)
    {
        foreach ($items as $index => $item) {
            NavbarItem::where('id', $item['id'])->update([
                'parent_id' => $parentId,
                'order' => $index + 1
            ]);
            
            if (isset($item['children']) && count($item['children']) > 0) {
                $this->updateOrder($item['children'], $item['id']);
            }
        }
    }
}
