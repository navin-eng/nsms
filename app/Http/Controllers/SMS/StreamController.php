<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;

use App\Models\Stream;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class StreamController extends Controller
{
    public function index()
    {
        $streams = Stream::all();
        return view('backend.pages.sms.streams.index', compact('streams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        Stream::create($data);
        Alert::success('Success', 'Stream added successfully');
        return back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        Stream::findOrFail($id)->update($data);
        Alert::success('Success', 'Stream updated successfully');
        return back();
    }

    public function destroy($id)
    {
        Stream::findOrFail($id)->delete();
        Alert::success('Success', 'Stream deleted successfully');
        return back();
    }
}
