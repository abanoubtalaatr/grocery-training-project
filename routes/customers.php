<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;

Route::middleware(['auth'])->prefix('my-admin')->group(function () {

    Route::resource('customers', CustomerController::class)->only([ 'index', 'show' ]);

});