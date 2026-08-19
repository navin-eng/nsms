<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffDocumentController extends Controller
{
    public function store(Request $request, Staff $staff)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        $path = $request->file('document')->store('staff/documents', 'public');

        $staff->documents()->create([
            'title' => $request->title,
            'document_path' => $path
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function destroy(StaffDocument $document)
    {
        if (Storage::disk('public')->exists($document->document_path)) {
            Storage::disk('public')->delete($document->document_path);
        }
        
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
