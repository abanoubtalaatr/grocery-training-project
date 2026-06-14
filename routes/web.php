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

Route::get('/failed-jobs', function () {
    if (config('queue.default') !== 'database') {
        abort(403, 'This dashboard is only available when using the database queue driver.');
    }

    try {
        $failedJobs = \Illuminate\Support\Facades\DB::table('failed_jobs')->orderByDesc('failed_at')->get();
    } catch (\Exception $e) {
        $failedJobs = collect(); // Fallback if table doesn't exist
    }

    return view('failed-jobs', compact('failedJobs'));
});
