<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParentPortal\DashboardController;
use App\Http\Controllers\ParentPortal\ProfileController;
use App\Http\Controllers\ParentPortal\AttendanceController;
use App\Http\Controllers\ParentPortal\ResultController;
use App\Http\Controllers\ParentPortal\FeeController;
use App\Http\Controllers\ParentPortal\NoticeController;

// All routes are prefixed with /parent and use 'role:Parent' middleware via web.php

// Child Selection Context
Route::post('/set-child', [DashboardController::class, 'setChildContext'])->name('parent.set-child');

Route::middleware('activeChild')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('parent.dashboard');
    
    Route::get('/profile', [ProfileController::class, 'index'])->name('parent.profile');
    
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('parent.attendance');
    
    Route::get('/results', [ResultController::class, 'index'])->name('parent.results');
    Route::get('/results/print/{exam_id}', [ResultController::class, 'printMarkSheet'])->name('parent.results.print');
    Route::get('/results/transcript/{year_id}', [ResultController::class, 'printTranscript'])->name('parent.results.transcript');
    
    Route::get('/fees', [FeeController::class, 'index'])->name('parent.fees');
    
    Route::get('/notices', [NoticeController::class, 'index'])->name('parent.notices');

    Route::get('/homework', [\App\Http\Controllers\ParentPortal\HomeworkController::class, 'index'])->name('parent.homework');
});
