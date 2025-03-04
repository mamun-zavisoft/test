<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Architecture\Services\ServiceContainer;

Route::middleware('auth')->name('admin.')->group(function () {
    Route::get('get-racks-for-product/{productId}', [StockController::class, 'getRacksForProduct'])->name('stock.get-racks-for-product');
    Route::get('get-drawers-for-rack/{productId}/{rackId}', [StockController::class, 'getDrawersForRack'])->name('stock.get-drawers-for-rack');
    Route::get('get-stock-info/{productId}/{rackId}/{drawerId}', [StockController::class, 'getStockInfo'])->name('stock.get-stock-info');

    Route::post('purchase/{id}/payment', [PurchaseController::class, 'payment'])->name('purchases.payment');
    Route::post('service/{id}/payment', [ServiceContainer::class, 'payment'])->name('services.payment');
});