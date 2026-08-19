<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SMS\AcademicYearController;
use App\Http\Controllers\SMS\StreamController;
use App\Http\Controllers\SMS\AcademicClassController;
use App\Http\Controllers\SMS\SectionController;
use App\Http\Controllers\SMS\SubjectController;
use App\Http\Controllers\SMS\DepartmentController;
use App\Http\Controllers\SMS\DesignationController;
use App\Http\Controllers\SMS\StaffController;
use App\Http\Controllers\SMS\StaffDocumentController;
use App\Http\Controllers\SMS\StudentController;
use App\Http\Controllers\SMS\StudentDocumentController;

// --- STAFF MANAGEMENT (HR) ---
Route::resource('departments', DepartmentController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::resource('designations', DesignationController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::resource('staff', StaffController::class, ['as' => 'sms']);
Route::post('staff/{staff}/documents', [StaffDocumentController::class, 'store'])->name('sms.staff.documents.store');
Route::delete('staff/documents/{document}', [StaffDocumentController::class, 'destroy'])->name('sms.staff.documents.destroy');

// --- STUDENT MANAGEMENT ---
Route::get('students/{student}/print', [StudentController::class, 'print'])->name('sms.students.print');
Route::resource('students', StudentController::class, ['as' => 'sms']);
Route::post('students/{student}/documents', [StudentDocumentController::class, 'store'])->name('sms.students.documents.store');
Route::delete('students/documents/{document}', [StudentDocumentController::class, 'destroy'])->name('sms.students.documents.destroy');

// Academic Structure
Route::resource('academic-years', AcademicYearController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::post('academic-years/{id}/make-active', [AcademicYearController::class, 'makeActive'])->name('sms.academic-years.active');

Route::resource('streams', StreamController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::resource('academic-classes', AcademicClassController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::resource('sections', SectionController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::resource('subjects', SubjectController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
