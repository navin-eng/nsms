<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class CounterController extends Controller
{
    public function index()
    {
        $counter = Counter::first();
        return view('backend.pages.counter.table', compact('counter'));
    }

    protected function rules()
    {
        return [
            'section_tag' => 'required|string|max:255',
            'section_title' => 'required|string|max:255',
            'section_description' => 'nullable|string|max:500',
            'title1' => 'required|string|max:255',
            'counter1' => 'required|numeric|min:0',
            'suffix1' => 'nullable|string|max:20',
            'icon1' => 'nullable|string|max:255',
            'title2' => 'required|string|max:255',
            'counter2' => 'required|numeric|min:0',
            'suffix2' => 'nullable|string|max:20',
            'icon2' => 'nullable|string|max:255',
            'title3' => 'required|string|max:255',
            'counter3' => 'required|numeric|min:0',
            'suffix3' => 'nullable|string|max:20',
            'icon3' => 'nullable|string|max:255',
            'title4' => 'required|string|max:255',
            'counter4' => 'required|numeric|min:0',
            'suffix4' => 'nullable|string|max:20',
            'icon4' => 'nullable|string|max:255',
        ];
    }

    protected function payload(Request $request)
    {
        return [
            'section_tag' => $request->section_tag,
            'section_title' => $request->section_title,
            'section_description' => $request->section_description,
            'title1' => $request->title1,
            'counter1' => $request->counter1,
            'suffix1' => $request->suffix1 ?: '+',
            'icon1' => $request->icon1 ?: 'fa-solid fa-users',
            'title2' => $request->title2,
            'counter2' => $request->counter2,
            'suffix2' => $request->suffix2 ?: '+',
            'icon2' => $request->icon2 ?: 'fa-solid fa-graduation-cap',
            'title3' => $request->title3,
            'counter3' => $request->counter3,
            'suffix3' => $request->suffix3 ?: '+',
            'icon3' => $request->icon3 ?: 'fa-solid fa-trophy',
            'title4' => $request->title4,
            'counter4' => $request->counter4,
            'suffix4' => $request->suffix4 ?: '+',
            'icon4' => $request->icon4 ?: 'fa-solid fa-book',
        ];
    }

    public function store(Request $request)
    {
        $request->validate($this->rules());

        $save = Counter::create($this->payload($request));

        if ($save) {

            Alert::success('Saved', 'counter saved successfully');
            return back();
        } else {
            Alert::error('oops', 'counter couldnot saved');
            return back();
        }
    }
    public function update(Request $request,$id)
    {
        $request->validate($this->rules());

        $counter = Counter::findOrFail($id);
        $save = $counter->update($this->payload($request));

        if ($save) {

            Alert::success('Saved', 'counter updated successfully');
            return back();
        } else {
            Alert::error('oops', 'counter couldnot updated');
            return back();
        }
    }
}
