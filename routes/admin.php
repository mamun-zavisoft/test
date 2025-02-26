<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DrawerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\ServiceChartController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VehiclesController;
use App\Http\Controllers\ZoneController;
use Illuminate\Support\Facades\Route;

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

    // single action routes
    Route::get('/product/search', [ProductController::class, 'search'])->name('products.search');

    Route::controller(SettingController::class)->group(function () {
        Route::delete('/media/{modelName}/{id}', 'destroyMedia')->name('media.destroy');
    });
});
