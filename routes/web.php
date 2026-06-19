<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\SupportReportController;
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

        Route::resource('/users', UserController::class);

        Route::resource('/categories', CategoryController::class);

        Route::resource('/meals', MealController::class);

        Route::resource('orders', OrderController::class)->except(['create', 'store']);

        Route::resource('subcategories', SubcategoryController::class);
        Route::resource(
            'reviews',
            ReviewController::class
        )->only([
                    'index',
                    'show',
                    'destroy',
                ]);

        Route::patch(
            'reviews/{review}/approve',
            [ReviewController::class, 'approve']
        )->name('reviews.approve');

        Route::patch(
            'reviews/{review}/reject',
            [ReviewController::class, 'reject']
        )->name('reviews.reject');

        Route::resource('offers', OfferController::class);

        Route::resource('faqs', FaqController::class);

        Route::resource(
            'contact-messages',
            ContactMessageController::class
        )->only([
            'index',
            'show',
            'destroy',
        ]);

        Route::patch(
            'contact-messages/{contactMessage}/read',
            [ContactMessageController::class, 'markAsRead']
        )->name('contact-messages.read');

        Route::patch(
            'contact-messages/{contactMessage}/replied',
            [ContactMessageController::class, 'markAsReplied']
        )->name('contact-messages.replied');

        Route::patch(
            'contact-messages/{contactMessage}/spam',
            [ContactMessageController::class, 'markAsSpam']
        )->name('contact-messages.spam');


        Route::resource(
            'support-reports',
            SupportReportController::class
        )->only([
            'index',
            'show',
            'destroy',
        ]);

        Route::patch(
            'support-reports/{supportReport}/in-progress',
            [SupportReportController::class, 'markInProgress']
        )->name('support-reports.in-progress');

        Route::patch(
            'support-reports/{supportReport}/resolved',
            [SupportReportController::class, 'markResolved']
        )->name('support-reports.resolved');

        Route::get(
            '/settings',
            [SettingController::class, 'edit']
        )->name('settings.edit');

        Route::put(
            '/settings',
            [SettingController::class, 'update']
        )->name('settings.update');

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
