<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\LibraryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class LibraryCategoryController extends Controller
{
    public function index()
    {
        $categories = LibraryCategory::withCount('books')->orderBy('name')->get();
        return view('backend.pages.sms.library.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:library_categories,name',
            'description' => 'nullable|string',
        ]);

        LibraryCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        Alert::success('Success', 'Category added successfully');
        return back();
    }

    public function update(Request $request, $id)
    {
        $category = LibraryCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:library_categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        Alert::success('Success', 'Category updated successfully');
        return back();
    }

    public function destroy($id)
    {
        $category = LibraryCategory::withCount('books')->findOrFail($id);
        if ($category->books_count > 0) {
            Alert::error('Error', 'Cannot delete category because it has books associated with it.');
            return back();
        }
        
        $category->delete();
        Alert::success('Success', 'Category deleted successfully');
        return back();
    }
}
