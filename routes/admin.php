<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DrawerController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\HubImportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceChartController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockPurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VehicleFuelController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\VehicleReportController;
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
    Route::resource('/services', ServiceController::class);
    Route::resource('/sales', SaleController::class);
    Route::resource('/vehicle-fuels', VehicleFuelController::class);
    Route::resource('/hubs', HubController::class);
    Route::resource('/vehicle-models', VehicleModelController::class);

    // single action routes
    Route::get('/product/search', [ProductController::class, 'search'])->name('products.search');
    Route::put('/purchases/statusChange/{id}', [PurchaseController::class, 'statusChange'])->name('purchases.statusChange');
    Route::get('/stock-purchases/{id}', [StockPurchaseController::class, 'create'])->name('stock-purchases.create');
    Route::post('/stock-purchases/{id}/store', [StockPurchaseController::class, 'store'])->name('stock-purchases.store');
    Route::get('/service/{id}/details', [ServiceController::class, 'printInvoice'])->name('service.print');
    Route::patch('/brands/{id}/status', [BrandController::class, 'updateStatus'])->name('brands.status');
    Route::put('/password/update', [PasswordController::class, 'update'])->name('password.update');

    // ajax call routes
    Route::get('/drawers/fetch/{rackId}', [DrawerController::class, 'fetchDrawersByRack'])->name('racks.fetchDrawers');
    Route::get('/purchase/view/payments/{id}', [PurchaseController::class, 'view_payments'])->name('purchase.view.payments');
    Route::get('/service/view/payments/{id}', [ServiceController::class, 'view_payments'])->name('service.view.payments');
    Route::get('/search/vehicle', [VehiclesController::class, 'searchVehicle'])->name('search.vehicle');

    // report routes
    Route::get('/vehicle-reports', [VehicleReportController::class, 'index'])->name('vehicle.reports.index');
    Route::get('/vehicle-reports/data', [VehicleReportController::class, 'report'])->name('vehicle.reports.data');

    // system general settings
    Route::controller(SettingController::class)->group(function () {
        Route::delete('/media/{modelName}/{id}', 'destroyMedia')->name('media.destroy');
    });

    Route::post('/import-hubs', [HubImportController::class, 'import'])->name('import.hubs');

});
