<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Provider\ProviderAuthController;
use App\Http\Controllers\Provider\SchoolManagementController;

/*
|--------------------------------------------------------------------------
| SaaS Provider Portal Routes (God Mode)
|--------------------------------------------------------------------------
|
| Central administration system for the SaaS provider to manage schools,
| subscriptions, enabled modules, status lifecycle, and platform health.
|
*/

Route::prefix('provider')->name('provider.')->group(function () {
    // Auth Routes
    Route::get('/login', [ProviderAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ProviderAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [ProviderAuthController::class, 'logout'])->name('logout');

    // Protected God-Mode Routes
    Route::middleware('auth:provider')->group(function () {
        Route::get('/dashboard', [SchoolManagementController::class, 'dashboard'])->name('dashboard');
        
        // Schools Management
        Route::get('/schools', [SchoolManagementController::class, 'index'])->name('schools.index');
        Route::get('/schools/create', [SchoolManagementController::class, 'create'])->name('schools.create');
        Route::post('/schools', [SchoolManagementController::class, 'store'])->name('schools.store');
        Route::get('/schools/{id}', [SchoolManagementController::class, 'show'])->name('schools.show');
        Route::post('/schools/{id}/status', [SchoolManagementController::class, 'updateStatus'])->name('schools.status');
        Route::post('/schools/{id}/modules', [SchoolManagementController::class, 'updateModules'])->name('schools.modules');
        Route::post('/schools/{id}/reset-password', [SchoolManagementController::class, 'resetSchoolAdminPassword'])->name('schools.reset_password');
    });
});
