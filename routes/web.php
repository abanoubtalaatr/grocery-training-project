<?php

use App\Http\Controllers\StripePaymentCallbackController;
use App\Http\Controllers\WebChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Profile\AddressesController;
use App\Http\Controllers\Profile\HelpSupportController;
use App\Http\Controllers\Profile\LoyaltyRewardsController;
use App\Http\Controllers\Profile\OrderHistoryController;
use App\Http\Controllers\Profile\PaymentWalletController;
use App\Http\Controllers\Profile\PersonalInfoController;
use App\Http\Controllers\Profile\SecurityController;
use App\Http\Controllers\Profile\SettingsController;
use App\Http\Controllers\Profile\SmartListsController;

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


/**
 * New Routes for the Dashbaord
 */
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
  ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile / Account sections
| TODO: LATER:
|--------------------------------------------------------------------------
*/

// Route::prefix('profile')->name('profile.')->group(function () {

//   // Personal Info
//   Route::get('/personal-info', [PersonalInfoController::class, 'index'])
//     ->name('personal-info');

//   // Payment & Wallet
//   Route::get('/payment-wallet', [PaymentWalletController::class, 'index'])
//     ->name('payment-wallet');

//   // Order History
//   Route::get('/order-history', [OrderHistoryController::class, 'index'])
//     ->name('order-history');

//   // Smart Lists
//   Route::get('/smart-lists', [SmartListsController::class, 'index'])
//     ->name('smart-lists');

//   // Addresses
//   Route::get('/addresses', [AddressesController::class, 'index'])
//     ->name('addresses');

//   // Security & Login
//   Route::get('/security', [SecurityController::class, 'index'])
//     ->name('security');

//   // Loyalty & Rewards
//   Route::get('/loyalty-rewards', [LoyaltyRewardsController::class, 'index'])
//     ->name('loyalty-rewards');

//   // Help & Support
//   Route::get('/help-support', [HelpSupportController::class, 'index'])
//     ->name('help-support');

//   // Settings
//   Route::get('/settings', [SettingsController::class, 'index'])
//     ->name('settings');
// });
