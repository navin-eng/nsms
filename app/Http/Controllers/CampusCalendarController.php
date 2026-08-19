<?php

namespace App\Http\Controllers;

use App\Models\CampusCalendarEntry;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CampusCalendarController extends Controller
{
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'entry_type' => 'required|in:holiday,exam,test,cca_eca,result,other',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'result_link' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function index()
    {
        $entries = CampusCalendarEntry::orderBy('start_date')->get();

        return view('backend.pages.calendar.index', compact('entries'));
    }

    public function store(Request $request)
    {
        CampusCalendarEntry::create($request->validate($this->rules()));

        Alert::success('Saved', 'Calendar entry added successfully');

        return back();
    }

    public function update(Request $request, $id)
    {
        $entry = CampusCalendarEntry::findOrFail($id);
        $entry->update($request->validate($this->rules()));

        Alert::success('Updated', 'Calendar entry updated successfully');

        return back();
    }

    public function toggleStatus($id)
    {
        $entry = CampusCalendarEntry::findOrFail($id);
        $entry->status = !$entry->status;
        $entry->save();

        Alert::success('Updated', 'Calendar entry status changed');

        return back();
    }

    public function destroy($id)
    {
        CampusCalendarEntry::findOrFail($id)->delete();

        Alert::success('Deleted', 'Calendar entry deleted successfully');

        return back();
    }
}
