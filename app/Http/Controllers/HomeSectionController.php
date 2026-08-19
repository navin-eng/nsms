<?php

namespace App\Http\Controllers;

use App\Models\HomeSection;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class HomeSectionController extends Controller
{
    public function index()
    {
        $sections = HomeSection::orderBy('sort_order')->get();

        return view('backend.pages.home_sections.index', compact('sections'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:home_sections,id',
            'sections.*.label' => 'required|string|max:255',
            'sections.*.sort_order' => 'required|integer|min:1',
        ]);

        foreach ($request->sections as $sectionData) {
            HomeSection::where('id', $sectionData['id'])->update([
                'label' => $sectionData['label'],
                'sort_order' => $sectionData['sort_order'],
                'is_visible' => isset($sectionData['is_visible']) ? 1 : 0,
            ]);
        }

        Alert::success('Updated', 'Homepage layout updated successfully');

        return back();
    }
}
