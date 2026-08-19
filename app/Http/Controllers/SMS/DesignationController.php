<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::orderBy('name')->get();
        return view('backend.pages.sms.staff.designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:designations,name',
            'description' => 'nullable|string'
        ]);

        Designation::create($request->all());

        return redirect()->back()->with('success', 'Designation created successfully.');
    }

    public function update(Request $request, Designation $designation)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:designations,name,' . $designation->id,
            'description' => 'nullable|string'
        ]);

        $designation->update($request->all());

        return redirect()->back()->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        if ($designation->staff()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete designation. Staff members are assigned to it.');
        }

        $designation->delete();
        return redirect()->back()->with('success', 'Designation deleted successfully.');
    }
}
