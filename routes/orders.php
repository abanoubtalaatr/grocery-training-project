<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;

Route::middleware(['auth']) ->prefix('my-admin') ->group(function () {

    Route::resource('orders', OrderController::class) ->only([ 'index','show','update' ]);

});