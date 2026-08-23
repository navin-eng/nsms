<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\HostelRoom;
use App\Models\HostelBed;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class HostelRoomController extends Controller
{
    public function index()
    {
        $rooms = HostelRoom::with('hostel', 'beds')->get();
        $hostels = Hostel::all();
        return view('backend.pages.sms.hostel.rooms', compact('rooms', 'hostels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_number' => 'required|string|max:255',
            'room_type' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'cost_per_bed' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $room = HostelRoom::create($data);

            // Auto-generate beds
            for ($i = 1; $i <= $data['capacity']; $i++) {
                HostelBed::create([
                    'hostel_room_id' => $room->id,
                    'bed_number' => 'Bed ' . $i,
                    'status' => 'Available'
                ]);
            }

            DB::commit();
            Alert::success('Success', 'Room and beds added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Failed to create room: ' . $e->getMessage());
        }

        return back();
    }

    public function update(Request $request, $id)
    {
        $room = HostelRoom::findOrFail($id);
        
        $data = $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_number' => 'required|string|max:255',
            'room_type' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'cost_per_bed' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            
            $oldCapacity = $room->capacity;
            $newCapacity = $data['capacity'];
            
            $room->update($data);

            if ($newCapacity > $oldCapacity) {
                // Add more beds
                for ($i = $oldCapacity + 1; $i <= $newCapacity; $i++) {
                    HostelBed::create([
                        'hostel_room_id' => $room->id,
                        'bed_number' => 'Bed ' . $i,
                        'status' => 'Available'
                    ]);
                }
            } elseif ($newCapacity < $oldCapacity) {
                // We should only remove beds if they are not allocated.
                // For safety, let's just alert the user or let them manually manage if reducing capacity.
                // To keep it simple, we won't auto-delete beds to prevent data loss.
                Alert::warning('Warning', 'Capacity reduced. Please manually delete unused beds if needed.');
            }

            DB::commit();
            Alert::success('Success', 'Room updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Failed to update room: ' . $e->getMessage());
        }

        return back();
    }

    public function destroy($id)
    {
        $room = HostelRoom::findOrFail($id);
        
        // Check if any bed is allocated
        $hasAllocations = $room->beds()->where('status', 'Allocated')->exists();
        if ($hasAllocations) {
            Alert::error('Error', 'Cannot delete room with allocated beds.');
            return back();
        }

        $room->delete(); // Cascade deletes beds if set up in DB, or manual delete
        Alert::success('Success', 'Room deleted successfully.');
        return back();
    }
}
