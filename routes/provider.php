<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Provider\ProviderAuthController;
use App\Http\Controllers\Provider\SchoolManagementController;
use App\Http\Controllers\Provider\ProviderUserController;
use App\Http\Controllers\Provider\ProviderMfaController;
use App\Http\Controllers\Provider\SettingsController;

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
    Route::post('/login', [ProviderAuthController::class, 'login'])->middleware('throttle:provider-login')->name('login.submit');
    Route::post('/logout', [ProviderAuthController::class, 'logout'])->name('logout');

    // Protected routes requiring standard provider auth
    Route::middleware('auth:provider')->group(function () {
        // MFA Challenge Flow
        Route::get('/2fa', [ProviderMfaController::class, 'showChallenge'])->name('2fa.challenge');
        Route::post('/2fa', [ProviderMfaController::class, 'verify'])->name('2fa.verify');
        Route::post('/2fa/resend', [ProviderMfaController::class, 'resend'])->name('2fa.resend');

        // Fully Authenticated & MFA-Verified God-Mode Routes
        Route::middleware(\App\Http\Middleware\RequireProviderMFA::class)->group(function () {
            Route::get('/dashboard', [SchoolManagementController::class, 'dashboard'])->name('dashboard');
        
        // Schools Management
        Route::get('/schools', [SchoolManagementController::class, 'index'])->name('schools.index');
        Route::get('/schools/create', [SchoolManagementController::class, 'create'])->name('schools.create');
        Route::post('/schools', [SchoolManagementController::class, 'store'])->name('schools.store');
        Route::get('/schools/{id}', [SchoolManagementController::class, 'show'])->name('schools.show');
        Route::get('/schools/{id}/edit', [SchoolManagementController::class, 'edit'])->name('schools.edit');
        Route::put('/schools/{id}', [SchoolManagementController::class, 'update'])->name('schools.update');
        Route::post('/schools/{id}/renew', [SchoolManagementController::class, 'renewPackage'])->name('schools.renew');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

        // Billing
        Route::get('/invoices/{id}/print', [\App\Http\Controllers\Provider\BillingController::class, 'printInvoice'])->name('billing.print');
        Route::get('/invoices/{id}/download', [\App\Http\Controllers\Provider\BillingController::class, 'downloadPdf'])->name('billing.download');
    
        Route::post('/schools/{id}/status', [SchoolManagementController::class, 'updateStatus'])->name('schools.status');
        Route::post('/schools/{id}/modules', [SchoolManagementController::class, 'updateModules'])->name('schools.modules');
        Route::post('/schools/{id}/reset-password', [SchoolManagementController::class, 'resetSchoolAdminPassword'])->name('schools.reset_password');

        // Provider User Management
        Route::get('/users', [ProviderUserController::class, 'index'])->name('users.index');
        Route::post('/users', [ProviderUserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [ProviderUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [ProviderUserController::class, 'destroy'])->name('users.destroy');

        // Security Settings
        Route::get('/security', [ProviderMfaController::class, 'securitySettings'])->name('security.settings');
        Route::post('/security/totp/enable', [ProviderMfaController::class, 'enableTotp'])->name('security.totp.enable');
        Route::get('/security/totp/recovery', [ProviderMfaController::class, 'showRecoveryCodes'])->name('security.totp.recovery');
        Route::post('/security/totp/confirm', [ProviderMfaController::class, 'confirmRecoveryCodes'])->name('security.totp.confirm');
        Route::match(['get', 'post'], '/security/revert-email', [ProviderMfaController::class, 'revertToEmail'])->name('security.revert_email');
        });
    });
});
