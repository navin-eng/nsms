<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::orderBy('name')->get();
        return view('accounting.vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        Vendor::create($data);
        return back()->with('success', 'Vendor added successfully.');
    }

    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', false);
        $vendor->update($data);
        return back()->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return back()->with('success', 'Vendor deleted successfully.');
    }
}
