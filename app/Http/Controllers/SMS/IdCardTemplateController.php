<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\IdCardTemplate;
use Illuminate\Http\Request;

class IdCardTemplateController extends Controller
{
    public function index()
    {
        $templates = IdCardTemplate::all();
        return view('backend.pages.sms.id_cards.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('backend.pages.sms.id_cards.templates.designer');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:student,staff',
            'layout' => 'required|in:portrait,landscape',
            'html_content' => 'required|string',
            'css_content' => 'nullable|string',
        ]);

        $template = IdCardTemplate::create($request->all());

        if ($request->has('is_default')) {
            IdCardTemplate::where('type', $request->type)->update(['is_default' => false]);
            $template->update(['is_default' => true]);
        }

        return redirect()->route('sms.id-cards.templates.index')->with('success', 'Template created successfully.');
    }

    public function edit($id)
    {
        $template = IdCardTemplate::findOrFail($id);
        return view('backend.pages.sms.id_cards.templates.designer', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = IdCardTemplate::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:student,staff',
            'layout' => 'required|in:portrait,landscape',
            'html_content' => 'required|string',
            'css_content' => 'nullable|string',
        ]);

        $template->update($request->all());

        if ($request->has('is_default')) {
            IdCardTemplate::where('type', $request->type)->where('id', '!=', $id)->update(['is_default' => false]);
            $template->update(['is_default' => true]);
        }

        return redirect()->route('sms.id-cards.templates.index')->with('success', 'Template updated successfully.');
    }

    public function destroy($id)
    {
        IdCardTemplate::findOrFail($id)->delete();
        return redirect()->route('sms.id-cards.templates.index')->with('success', 'Template deleted successfully.');
    }
}
