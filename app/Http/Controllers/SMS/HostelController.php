<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\Staff;
use RealRashid\SweetAlert\Facades\Alert;

class HostelController extends Controller
{
    public function index()
    {
        $hostels = Hostel::with('warden')->get();
        $wardens = Staff::where('status', 'Active')->get();
        return view('backend.pages.sms.hostel.index', compact('hostels', 'wardens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Boys,Girls,Mixed',
            'address' => 'nullable|string|max:255',
            'warden_id' => 'nullable|exists:staff,id',
            'warden_name' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        Hostel::create($data);
        Alert::success('Success', 'Hostel added successfully.');
        return back();
    }

    public function update(Request $request, $id)
    {
        $hostel = Hostel::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Boys,Girls,Mixed',
            'address' => 'nullable|string|max:255',
            'warden_id' => 'nullable|exists:staff,id',
            'warden_name' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        $hostel->update($data);
        Alert::success('Success', 'Hostel updated successfully.');
        return back();
    }

    public function destroy($id)
    {
        $hostel = Hostel::findOrFail($id);
        if ($hostel->rooms()->count() > 0) {
            Alert::error('Error', 'Cannot delete hostel because it has rooms.');
            return back();
        }
        
        $hostel->delete();
        Alert::success('Success', 'Hostel deleted successfully.');
        return back();
    }
}
