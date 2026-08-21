<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\LibrarySetting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LibrarySettingController extends Controller
{
    public function index()
    {
        $settings = LibrarySetting::firstOrCreate([], [
            'max_borrow_days_student' => 7,
            'max_borrow_days_staff' => 14,
            'fine_per_day' => 5.00,
            'max_books_student' => 2,
            'max_books_staff' => 5
        ]);

        return view('backend.pages.sms.library.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'max_borrow_days_student' => 'required|integer|min:1',
            'max_borrow_days_staff' => 'required|integer|min:1',
            'fine_per_day' => 'required|numeric|min:0',
            'max_books_student' => 'required|integer|min:1',
            'max_books_staff' => 'required|integer|min:1'
        ]);

        $settings = LibrarySetting::first();
        if($settings) {
            $settings->update($request->only([
                'max_borrow_days_student', 
                'max_borrow_days_staff', 
                'fine_per_day', 
                'max_books_student', 
                'max_books_staff'
            ]));
        } else {
            LibrarySetting::create($request->all());
        }

        Alert::success('Success', 'Library Settings updated successfully');
        return back();
    }
}
