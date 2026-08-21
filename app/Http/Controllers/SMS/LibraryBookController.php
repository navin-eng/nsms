<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\LibraryBook;
use App\Models\LibraryBookCopy;
use App\Models\LibraryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class LibraryBookController extends Controller
{
    public function index(Request $request)
    {
        $categories = LibraryCategory::orderBy('name')->get();
        
        $query = LibraryBook::with('category')->withCount('copies');
        
        if ($request->filled('category_id')) {
            $query->where('library_category_id', $request->category_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }
        
        $books = $query->latest()->paginate(15);
        return view('backend.pages.sms.library.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = LibraryCategory::orderBy('name')->get();
        return view('backend.pages.sms.library.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'library_category_id' => 'required|exists:library_categories,id',
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'rack_number' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'total_copies' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $cover_image = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $cover_image = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/library'), $cover_image);
        }

        $book = LibraryBook::create([
            'library_category_id' => $request->library_category_id,
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'isbn' => $request->isbn,
            'rack_number' => $request->rack_number,
            'price' => $request->price ?? 0,
            'total_copies' => $request->total_copies,
            'available_copies' => $request->total_copies,
            'description' => $request->description,
            'cover_image' => $cover_image,
        ]);

        // Generate Copies
        $copiesToInsert = [];
        for ($i = 1; $i <= $book->total_copies; $i++) {
            $copiesToInsert[] = [
                'library_book_id' => $book->id,
                'barcode' => 'LIB-' . date('Y') . '-' . str_pad($book->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'status' => 'available',
                'condition' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        LibraryBookCopy::insert($copiesToInsert);

        Alert::success('Success', 'Book and its copies added successfully');
        return redirect()->route('sms.library.books.index');
    }

    public function show($id)
    {
        $book = LibraryBook::with(['category', 'copies.activeIssue.borrower'])->findOrFail($id);
        return view('backend.pages.sms.library.books.show', compact('book'));
    }

    public function printBarcodes($id)
    {
        $book = LibraryBook::with('copies')->findOrFail($id);
        $setting = \App\Models\SiteSetting::first();
        return view('backend.pages.sms.library.books.print_barcodes', compact('book', 'setting'));
    }

    public function copyHistory($id)
    {
        $copy = LibraryBookCopy::with(['book', 'issues' => function($q) {
            $q->orderBy('issue_date', 'asc');
        }, 'issues.borrower'])->findOrFail($id);
        
        return view('backend.pages.sms.library.books.history', compact('copy'));
    }

    public function edit($id)
    {
        $book = LibraryBook::findOrFail($id);
        $categories = LibraryCategory::orderBy('name')->get();
        return view('backend.pages.sms.library.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = LibraryBook::findOrFail($id);

        $request->validate([
            'library_category_id' => 'required|exists:library_categories,id',
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'rack_number' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $cover_image = $book->cover_image;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $cover_image = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/library'), $cover_image);
        }

        $book->update([
            'library_category_id' => $request->library_category_id,
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'isbn' => $request->isbn,
            'rack_number' => $request->rack_number,
            'price' => $request->price ?? 0,
            'description' => $request->description,
            'cover_image' => $cover_image,
        ]);

        Alert::success('Success', 'Book updated successfully');
        return redirect()->route('sms.library.books.index');
    }

    public function destroy($id)
    {
        $book = LibraryBook::findOrFail($id);
        
        // Cannot delete if any copy is issued
        $issuedCopies = LibraryBookCopy::where('library_book_id', $book->id)->where('status', 'issued')->count();
        if ($issuedCopies > 0) {
            Alert::error('Error', 'Cannot delete book. Some copies are currently issued.');
            return back();
        }

        $book->delete();
        Alert::success('Success', 'Book deleted successfully');
        return back();
    }
}
