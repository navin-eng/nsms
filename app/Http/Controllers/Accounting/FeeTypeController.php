<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    public function index()
    {
        $types = FeeType::latest()->get();
        return view('accounting.fees.types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'types' => 'required|array|min:1',
            'types.*.name' => 'required|string|max:255|unique:fee_types,name',
            'types.*.description' => 'nullable|string'
        ]);

        foreach ($request->types as $typeData) {
            FeeType::create($typeData);
        }

        return back()->with('success', 'Fee Type(s) created successfully.');
    }

    public function update(Request $request, FeeType $feeType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:fee_types,name,' . $feeType->id,
            'description' => 'nullable|string'
        ]);

        $feeType->update($data);

        return back()->with('success', 'Fee Type updated successfully.');
    }

    public function destroy(FeeType $feeType)
    {
        $feeType->delete();
        return back()->with('success', 'Fee Type deleted successfully.');
    }
}
