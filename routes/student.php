<?php
use Illuminate\Support\Facades\Route;

// Student Portal Routes (Prefixed with /student by web.php)

Route::get('/dashboard', [\App\Http\Controllers\StudentPortal\DashboardController::class, 'index'])->name('student.dashboard');
Route::get('/profile', [\App\Http\Controllers\StudentPortal\ProfileController::class, 'index'])->name('student.profile');
Route::get('/attendance', [\App\Http\Controllers\StudentPortal\AttendanceController::class, 'index'])->name('student.attendance');
Route::get('/routine', [\App\Http\Controllers\StudentPortal\RoutineController::class, 'index'])->name('student.routine');
Route::get('/homework', [\App\Http\Controllers\StudentPortal\HomeworkController::class, 'index'])->name('student.homework');
Route::post('/homework/{id}/submit', [\App\Http\Controllers\StudentPortal\HomeworkController::class, 'submit'])->name('student.homework.submit');
Route::get('/materials', [\App\Http\Controllers\StudentPortal\MaterialController::class, 'index'])->name('student.materials');
Route::get('/exams', [\App\Http\Controllers\StudentPortal\ResultController::class, 'exams'])->name('student.exams');
Route::get('/results', [\App\Http\Controllers\StudentPortal\ResultController::class, 'results'])->name('student.results');
Route::get('/notices', [\App\Http\Controllers\StudentPortal\NoticeController::class, 'index'])->name('student.notices');
Route::get('/notices/{id}', [\App\Http\Controllers\StudentPortal\NoticeController::class, 'show'])->name('student.notices.show');
Route::get('/library', [\App\Http\Controllers\StudentPortal\LibraryController::class, 'index'])->name('student.library');

Route::post('/settings', [\App\Http\Controllers\StudentPortal\ProfileController::class, 'updateSettings'])->name('student.settings.update');

