<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\AboutUsFaq;
// use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AboutUsController extends Controller
{
    public function create()
    {
        $aboutus = AboutUs::first();
        $faqs = AboutUsFaq::orderBy('sort_order')->get();

        return view('backend.pages.aboutus.add', compact('aboutus', 'faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'desc' => 'required',
        ]);
        $condition = DB::table('about_us')->count();
        if($condition == 0)
        {
            $aboutus = new AboutUs();
            $aboutus->desc = $request->desc;
            $aboutus->save();
            Alert::success('Saved', 'aboutus saved successfully');
            return back();
        }
        else
        {
            Alert::error('error','Not Allowed');
        }

    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'desc' => 'required',
        ]);
        $aboutus = AboutUs::find($id);
        $aboutus->desc = $request->desc;
        $aboutus->update();
        Alert::success('Saved', 'aboutus updated successfully');
        return back();

    }

    public function faqStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'required|integer|min:1',
        ]);

        AboutUsFaq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order,
            'status' => 1,
        ]);

        Alert::success('Saved', 'FAQ added successfully');

        return back();
    }

    public function faqUpdate(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'required|integer|min:1',
        ]);

        $faq = AboutUsFaq::findOrFail($id);
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order,
        ]);

        Alert::success('Updated', 'FAQ updated successfully');

        return back();
    }

    public function faqStatus($id)
    {
        $faq = AboutUsFaq::findOrFail($id);
        $faq->status = $faq->status ? 0 : 1;
        $faq->save();

        Alert::success('Updated', 'FAQ status changed');

        return back();
    }

    public function faqDestroy($id)
    {
        AboutUsFaq::findOrFail($id)->delete();

        Alert::success('Deleted', 'FAQ deleted successfully');

        return back();
    }
}
