<?php

namespace App\Http\Controllers;

use App\Models\Privacypolicy;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class PrivacypolicyController extends Controller
{
    public function create()
    {
        $privacy = Privacypolicy::first();

        return view('backend.pages.privacy.add', compact('privacy'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'desc' => 'required',
        ]);
        $condition = DB::table('privacypolicies')->count();
        if($condition == 0)
        {
            $privacy = new Privacypolicy();
            $privacy->desc = $request->desc;
            $privacy->save();
            Alert::success('Saved', 'privacy saved successfully');
            return back();
        }
        else
        {
            Alert::error('error','Not Allowed');
            return back();
        }

    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'desc' => 'required',
        ]);
        $privacy = Privacypolicy::findOrFail($id);
        $privacy->desc = $request->desc;
        $privacy->update();
        Alert::success('Saved', 'privacy updated successfully');
        return back();

    }
}
