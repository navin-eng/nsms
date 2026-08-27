<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\InventoryCategoryController;
use App\Http\Controllers\Inventory\InventoryStoreController;
use App\Http\Controllers\Inventory\InventorySupplierController;
use App\Http\Controllers\Inventory\InventoryItemController;
use App\Http\Controllers\Inventory\InventoryPurchaseController;
use App\Http\Controllers\Inventory\InventoryIssueController;
use App\Http\Controllers\Inventory\InventoryMaintenanceController;

Route::prefix('admin/inventory')->name('admin.inventory.')->middleware(['auth', 'tenant.active', 'tenant.module:inventory'])->group(function () {
    // Categories
    Route::resource('categories', InventoryCategoryController::class)->except(['show']);
    
    // Stores
    Route::resource('stores', InventoryStoreController::class)->except(['show']);
    
    // Suppliers
    Route::resource('suppliers', InventorySupplierController::class)->except(['show']);
    
    // Items Master
    Route::resource('items', InventoryItemController::class)->except(['show']);
    
    // Stock / Purchases
    Route::resource('purchases', InventoryPurchaseController::class)->except(['show']);
    
    // Issue & Return
    Route::resource('issues', InventoryIssueController::class)->except(['show']);
    Route::post('issues/{issue}/return', [InventoryIssueController::class, 'returnItem'])->name('issues.return');
    
    // Maintenance & Damages
    Route::resource('maintenance', InventoryMaintenanceController::class)->except(['show']);
});
