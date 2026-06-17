<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\StripePaymentCallbackController;
use App\Http\Controllers\WebChatController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get(
            '/',
            [DashboardController::class, 'index']
        )->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get(
            '/categories',
            [CategoryController::class, 'index']
        )->name('categories.index');

        Route::get(
            '/meals',
            [MealController::class, 'index']
        )->name('meals.index');

        Route::get(
            '/orders',
            [OrderController::class, 'index']
        )->name('orders.index');

    });

Route::get('/language/{locale}', function ($locale) {

    abort_unless(
        in_array($locale, ['ar', 'en']),
        404
    );

    session()->put('locale', $locale);

    return back();

})->name('language.switch');
// Route::get('/admin', function () {
//     return 'Admin Dashboard Works';
// });
