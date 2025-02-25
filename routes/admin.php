<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ZoneController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->name('admin.')->group(function () {
    Route::resource('/brands', BrandController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/zones', ZoneController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/racks', RackController::class);
});

