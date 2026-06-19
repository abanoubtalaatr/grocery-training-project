<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Profile\AddressesController;
use App\Http\Controllers\Admin\Profile\HelpSupportController;
use App\Http\Controllers\Admin\Profile\LoyaltyRewardsController;
use App\Http\Controllers\Admin\Profile\OrderHistoryController;
use App\Http\Controllers\Admin\Profile\PaymentWalletController;
use App\Http\Controllers\Admin\Profile\PersonalInfoController;
use App\Http\Controllers\Admin\Profile\SecurityController;
use App\Http\Controllers\Admin\Profile\SettingsController;
use App\Http\Controllers\Admin\Profile\SmartListsController;
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
    'name'          => config('app.name'),
    'message'       => 'Welcome to Grocery API',
    'version'       => '1.0.0',
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

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile / Account sections
|--------------------------------------------------------------------------
*/
Route::prefix('profile')->name('profile.')->group(function () {

  // Personal Info
  Route::get('/personal-info', [PersonalInfoController::class, 'index'])->name('personal-info');
  Route::put('/personal-info', [PersonalInfoController::class, 'update'])->name('personal-info.update');

  // Payment & Wallet
  Route::get('/payment-wallet', [PaymentWalletController::class, 'index'])->name('payment-wallet');

  // Order History
  Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order-history');

  // Smart Lists
  Route::get('/smart-lists', [SmartListsController::class, 'index'])->name('smart-lists');
  Route::post('/smart-lists', [SmartListsController::class, 'store'])->name('smart-lists.store');
  Route::delete('/smart-lists/{id}', [SmartListsController::class, 'destroy'])->name('smart-lists.destroy');

  // Addresses
  Route::get('/addresses', [AddressesController::class, 'index'])->name('addresses');
  Route::post('/addresses', [AddressesController::class, 'store'])->name('addresses.store');
  Route::put('/addresses/{id}', [AddressesController::class, 'update'])->name('addresses.update');
  Route::patch('/addresses/{id}/default', [AddressesController::class, 'setDefault'])->name('addresses.default');
  Route::delete('/addresses/{id}', [AddressesController::class, 'destroy'])->name('addresses.destroy');

  // Security & Login
  Route::get('/security', [SecurityController::class, 'index'])->name('security');
  Route::put('/security/password', [SecurityController::class, 'updatePassword'])->name('security.password');

  // Loyalty & Rewards
  Route::get('/loyalty-rewards', [LoyaltyRewardsController::class, 'index'])->name('loyalty-rewards');

  // Help & Support
  Route::get('/help-support', [HelpSupportController::class, 'index'])->name('help-support');
  Route::post('/help-support', [HelpSupportController::class, 'store'])->name('help-support.store');

  // Settings / Preferences
  Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
  Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});
