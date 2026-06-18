<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SpecialNoteController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\SupportReportController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest authentication routes.
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    // Authenticated admin routes.
    Route::middleware('admin')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Catalog.
        Route::resource('categories', CategoryController::class);
        Route::resource('subcategories', SubcategoryController::class);
        Route::resource('meals', MealController::class);
        Route::resource('offers', OfferController::class);
        Route::resource('special-notes', SpecialNoteController::class)->except('show');

        // Users.
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::resource('users', UserController::class);

        // Orders.
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);

        // Reviews.
        Route::patch('reviews/{review}/toggle-approval', [ReviewController::class, 'toggleApproval'])->name('reviews.toggle-approval');
        Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'destroy']);

        // Content.
        Route::resource('faqs', FaqController::class);
        Route::resource('static-pages', StaticPageController::class);

        // Support & messaging.
        Route::patch('contact-messages/{contact_message}/status', [ContactMessageController::class, 'updateStatus'])->name('contact-messages.status');
        Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);

        Route::patch('support-reports/{support_report}/status', [SupportReportController::class, 'updateStatus'])->name('support-reports.status');
        Route::resource('support-reports', SupportReportController::class)->only(['index', 'show', 'destroy']);

        Route::resource('notifications', NotificationController::class)->only(['index', 'show', 'destroy']);

        // Settings (singleton).
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
