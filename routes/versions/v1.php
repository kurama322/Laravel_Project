<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1')->group(function () {
    Route::apiResource('products', \App\Http\Controllers\Api\v1\ProductsController::class);
});
