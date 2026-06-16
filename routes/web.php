<?php

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

// Redirect default login to Filament's login page
Route::redirect('/login', '/admin/login')->name('login');
Route::redirect('/admin', '/admin-dashboard');

// Custom Blade Admin Dashboard
Route::middleware(['web', 'auth', \App\Http\Middleware\EnsureUserIsAdmin::class])
    ->prefix('admin-dashboard')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/model/{model}', [\App\Http\Controllers\Admin\DashboardController::class, 'modelList'])->name('admin.model.list');
        Route::get('/model/{model}/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'modelDetail'])->name('admin.model.detail');
    });

