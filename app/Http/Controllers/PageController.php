<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->get();
        return view('backend.pages.page.table', compact('pages'));
    }

    public function create()
    {
        return view('backend.pages.page.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|min:2',
            'content' => 'required',
        ]);

        $page          = new Page();
        $page->title   = ucwords($request->title);
        $page->slug    = Str::slug($request->title);
        $page->content = $request->content;
        $page->status  = 1;
        $saved         = $page->save();

        if ($saved) {
            Alert::success('Saved', 'Page saved successfully');
            return redirect()->route('page.table');
        }

        Alert::error('Oops', 'Page could not be saved');
        return back();
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('backend.pages.page.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required|min:2',
            'content' => 'required',
        ]);

        $page          = Page::findOrFail($id);
        $page->title   = ucwords($request->title);
        $page->slug    = Str::slug($request->title);
        $page->content = $request->content;
        $saved         = $page->update();

        if ($saved) {
            Alert::success('Updated', 'Page updated successfully');
            return redirect()->route('page.table');
        }

        Alert::error('Oops', 'Page could not be updated');
        return back();
    }

    public function status($id)
    {
        $page = Page::findOrFail($id);
        $page->status = $page->status == 1 ? 0 : 1;
        $page->update();
        Alert::success('Updated', 'Page status changed');
        return back();
    }

    public function destroy($id)
    {
        Page::findOrFail($id)->delete();
        Alert::success('Deleted', 'Page deleted successfully');
        return redirect()->route('page.table');
    }
}
