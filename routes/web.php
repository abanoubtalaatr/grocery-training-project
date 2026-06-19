<?php

use App\Http\Controllers\StripePaymentCallbackController;
use App\Http\Controllers\WebChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
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

/********* V2 blade mvc routes ******************/

/******************** dashboard ********************/

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// ======= Custom Admin MVC =======


Route::prefix('my-admin')->name('admin.')->group(function () {

    // Guest only
    Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    });


});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});

 /******************** other route files  ***********************/
    if (file_exists(__DIR__.'/dashboard.php')) require __DIR__.'/dashboard.php';
    if (file_exists(__DIR__.'/categories.php')) require __DIR__.'/categories.php';
    if (file_exists(__DIR__.'/orders.php')) require __DIR__.'/orders.php';
    if (file_exists(__DIR__.'/customers.php')) require __DIR__.'/customers.php';
    if (file_exists(__DIR__.'/settings.php')) require __DIR__.'/settings.php';

/********************** old Code */
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
