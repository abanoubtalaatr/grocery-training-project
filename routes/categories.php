<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;

Route::middleware(['auth'])->prefix('my-admin')->name('admin.')->group(function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);

});