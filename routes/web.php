<?php

use App\Http\Controllers\StripePaymentCallbackController;
use App\Http\Controllers\WebChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get(
    '/language/{locale}',
    [LanguageController::class, 'switch']
)->name('language.switch');

Route::prefix('admin')
    ->middleware('locale')
    ->name('admin.')
    ->group(function () {

    Route::get(
    '/orders',
    [OrderController::class, 'index']
)->name('orders.index');

    Route::get(
    '/meals',
    [MealController::class, 'index']
)->name('meals.index');



        Route::get(
    '/subcategories',
    [SubcategoryController::class, 'index']
)->name('subcategories.index');

        Route::get(
    '/categories',
    [CategoryController::class, 'index']
        )->name('categories.index');

        Route::get(
            '/',
            [DashboardController::class, 'index']
        )->name('dashboard');

        Route::get(
            '/users',
            [UserController::class, 'index']
        )->name('users.index');
    });




Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'message' => 'Welcome to Grocery API',
        'version' => '1.0.0',
        'documentation' => '/api/documentation',
    ]);
});

Route::prefix('chat')->group(function () {
    Route::get('/', [WebChatController::class, 'index'])->name('chat');
    Route::post('/send', [WebChatController::class, 'send'])->name('chat.send');
    Route::post('/reset', [WebChatController::class, 'reset'])->name('chat.reset');
});

Route::prefix('payment')->group(function () {
    Route::get('/success', [StripePaymentCallbackController::class, 'success'])->name('payment.success');
    Route::get('/cancel', [StripePaymentCallbackController::class, 'cancel'])->name('payment.cancel');
});
