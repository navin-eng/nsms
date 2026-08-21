<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\AdmissionDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdmissionDocumentController extends Controller
{
    public function store(Request $request, AdmissionApplication $application)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $path = $request->file('document')->store('admission_documents', 'public');

        AdmissionDocument::create([
            'admission_application_id' => $application->id,
            'title' => $request->title,
            'document_path' => $path,
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function destroy(AdmissionDocument $document)
    {
        if (Storage::disk('public')->exists($document->document_path)) {
            Storage::disk('public')->delete($document->document_path);
        }
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }
}
