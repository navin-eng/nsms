<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Accounting Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the dedicated accounting
| system. These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group, "auth", and prefix "accounting".
|
*/

// We'll require 'auth' for now.
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Accounting\DashboardController::class, 'index'])->name('dashboard');

    // Expenses & Vendors
    Route::resource('vendors', \App\Http\Controllers\Accounting\VendorController::class)->except(['create', 'show', 'edit']);
    Route::resource('expenses', \App\Http\Controllers\Accounting\ExpenseController::class);
    Route::resource('budgets', \App\Http\Controllers\Accounting\BudgetController::class)->except(['create', 'show', 'edit']);

    // Banks
    Route::resource('banks', \App\Http\Controllers\Accounting\BankAccountController::class)->except(['show', 'create', 'edit']);
    Route::get('reconciliation', [\App\Http\Controllers\Accounting\BankAccountController::class, 'reconciliation'])->name('banks.reconciliation');
    Route::post('reconciliation', [\App\Http\Controllers\Accounting\BankAccountController::class, 'reconcile'])->name('banks.reconcile');

    // Reports
    Route::get('reports/income-statement', [\App\Http\Controllers\Accounting\ReportController::class, 'incomeStatement'])->name('reports.income-statement');
    Route::get('reports/balance-sheet', [\App\Http\Controllers\Accounting\ReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
    Route::get('reports/trial-balance', [\App\Http\Controllers\Accounting\ReportController::class, 'trialBalance'])->name('reports.trial-balance');
    
    // Fee Management (Moved from SMS to here)
    Route::prefix('fees')->name('fees.')->group(function () {
        Route::resource('types', \App\Http\Controllers\Accounting\FeeTypeController::class)->except(['show'])->names([
            'index' => 'fee-types.index',
            'create' => 'fee-types.create',
            'store' => 'fee-types.store',
            'edit' => 'fee-types.edit',
            'update' => 'fee-types.update',
            'destroy' => 'fee-types.destroy',
        ]);
        Route::resource('structures', \App\Http\Controllers\Accounting\FeeStructureController::class)->except(['show'])->names([
            'index' => 'fee-structures.index',
            'create' => 'fee-structures.create',
            'store' => 'fee-structures.store',
            'edit' => 'fee-structures.edit',
            'update' => 'fee-structures.update',
            'destroy' => 'fee-structures.destroy',
        ]);
        Route::post('structures/copy', [\App\Http\Controllers\Accounting\FeeStructureController::class, 'copy'])->name('fee-structures.copy');

        Route::get('invoices/generate', [\App\Http\Controllers\Accounting\FeeInvoiceController::class, 'generateIndex'])->name('invoices.generate');
        Route::post('invoices/generate', [\App\Http\Controllers\Accounting\FeeInvoiceController::class, 'generateProcess'])->name('invoices.generate.process');
        Route::get('invoices/{invoice}/print', [\App\Http\Controllers\Accounting\FeeInvoiceController::class, 'print'])->name('invoices.print');
        Route::resource('invoices', \App\Http\Controllers\Accounting\FeeInvoiceController::class);

        Route::get('payments/receipt/{payment}', [\App\Http\Controllers\Accounting\FeePaymentController::class, 'receipt'])->name('payments.receipt');
        Route::resource('payments', \App\Http\Controllers\Accounting\FeePaymentController::class)->only(['store', 'destroy']);

        Route::get('reports/outstanding', [\App\Http\Controllers\Accounting\FeeReportController::class, 'outstanding'])->name('reports.outstanding');
    });
});
