<?php

use App\Http\Controllers\Admin\DashboardController;
//use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Admin\CategoriesController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();



    Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin|moderator'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
   Route::resource('categories', \App\Http\Controllers\Admin\CategoriesController::class)->except(['show']);

    });

