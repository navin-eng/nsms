<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Support\Facades\Storage;

class StudentDocumentController extends Controller
{
    public function store(Request $request, Student $student)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $path = $request->file('document')->store('students/documents', 'public');

        $student->documents()->create([
            'title' => $request->title,
            'document_path' => $path,
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function destroy(StudentDocument $document)
    {
        if ($document->document_path && \Storage::disk('public')->exists($document->document_path)) {
            \Storage::disk('public')->delete($document->document_path);
        }
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }
}
