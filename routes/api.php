<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VehicleFuelController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->name('admin.')->group(function () {
    Route::get('get-racks-for-product/{productId}', [StockController::class, 'getRacksForProduct'])->name('stock.get-racks-for-product');
    Route::get('get-drawers-for-rack/{productId}/{rackId}', [StockController::class, 'getDrawersForRack'])->name('stock.get-drawers-for-rack');
    Route::get('get-stock-info/{productId}/{rackId}/{drawerId}', [StockController::class, 'getStockInfo'])->name('stock.get-stock-info');

    Route::post('purchase/{id}/payment', [PurchaseController::class, 'payment'])->name('purchases.payment');
    Route::post('service/{id}/payment', [ServiceController::class, 'payment'])->name('services.payment');
    
    // odo meter reading for a vehicle
    Route::post('current-reading', [VehicleFuelController::class, 'getCurrentOdometer'])->name('vehicle.getCurrentOdometer');
    Route::delete('delete-media', [DashboardController::class, 'deleteMedia'])->name('media.delete');
});
