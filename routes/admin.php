<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DrawerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\ServiceChartController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockPurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VehiclesController;
use App\Http\Controllers\ZoneController;
use App\Models\Drawer;
use App\Models\Purchase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Architecture\Services\ServiceContainer;

Route::middleware('auth')->name('admin.')->group(function () {
    Route::resource('/brands', BrandController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/zones', ZoneController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/racks', RackController::class);
    Route::resource('/service-charts', ServiceChartController::class);
    Route::resource('/drawers', DrawerController::class);
    Route::resource('/purchases', PurchaseController::class);
    Route::resource('/accounts', AccountController::class);
    Route::resource('/vehicles', VehiclesController::class);
    Route::resource('/services', ServiceController::class);
    
    // single action routes
    Route::get('/product/search', [ProductController::class, 'search'])->name('products.search');
    Route::put('/purchases/statusChange/{id}', [PurchaseController::class, 'statusChange'])->name('purchases.statusChange');
    Route::get('/stock-purchases/{id}', [StockPurchaseController::class, 'create'])->name('stock-purchases.create');
    Route::post('/stock-purchases/{id}/store', [StockPurchaseController::class, 'store'])->name('stock-purchases.store');

    // ajax call routes
    Route::get('/drawers/fetch/{rackId}', [DrawerController::class, 'fetchDrawersByRack'])->name('racks.fetchDrawers');
    Route::get('/purchase/view/payments/{id}', [PurchaseController::class, 'view_payments'])->name('purchase.view.payments');
    Route::get('/service/view/payments/{id}', [ServiceController::class, 'view_payments'])->name('service.view.payments');

    
    // system general settings
    Route::controller(SettingController::class)->group(function () {
        Route::delete('/media/{modelName}/{id}', 'destroyMedia')->name('media.destroy');
    });
});
