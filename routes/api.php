<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\Auth\GoogleAuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\SessionController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\OtpVerificationController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\AccountController;
use App\Http\Controllers\Api\BestSellerController;
use App\Http\Controllers\Api\CartClearController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CategoryMealController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\ChatbotSuggestionController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ContactStatsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DataManagementController;
use App\Http\Controllers\Api\ExploreMealController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\FrequencyMealController;
use App\Http\Controllers\Api\HotMealController;
use App\Http\Controllers\Api\LoyaltyController;
use App\Http\Controllers\Api\MealBrandController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\NewProductController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\NotificationSettingsController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderTrackingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProfileImageController;
use App\Http\Controllers\Api\ProfileSessionController;
use App\Http\Controllers\Api\RecommendedMealController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ReviewStatsController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SliderMealController;
use App\Http\Controllers\Api\SmartListController;
use App\Http\Controllers\Api\SmartListMealController;
use App\Http\Controllers\Api\SpecialNoteController;
use App\Http\Controllers\Api\StaticPageController;
use App\Http\Controllers\Api\StripeCheckoutController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\SubcategoryController;
use App\Http\Controllers\Api\SubcategoryMealController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\TodayDealController;
use App\Http\Controllers\Api\UserAppSettingsController;
use App\Http\Controllers\Api\SetDefaultAddressController;
use App\Http\Controllers\Api\V1\MealController as ApiMealController;
use App\Jobs\SendToEmailJob;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/send-invoice', function () {
    SendToEmailJob::dispatch();
    return response()->json(['message' => 'Invoice mail send successfully!'], 201);
});

Route::prefix('v1')->group(function () {
    Route::get('/meals', [ApiMealController::class, 'index']);
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

// Public routes - Authentication
Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [SessionController::class, 'store']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store']);
    Route::post('/verify-otp', [OtpVerificationController::class, 'store']);
    Route::post('/reset-password', [ResetPasswordController::class, 'store']);
    Route::post('/google', [GoogleAuthController::class, 'login']);
});

// Protected routes - Require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [SessionController::class, 'destroy']);
        Route::put('/password', [PasswordController::class, 'update']);
        Route::apiResource('account', AccountController::class)->only(['show', 'destroy'])->singleton();
    });

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::apiResource('image', ProfileImageController::class)->only(['store', 'destroy'])->singleton();
        Route::apiResource('sessions', ProfileSessionController::class)->only(['index', 'destroy']);
    });
    Route::apiResource('profile', ProfileController::class)->only(['show', 'update'])->singleton();

    // Address routes
    Route::apiResource('addresses', AddressController::class);
    Route::post('addresses/{address}/set-default', SetDefaultAddressController::class)->name('addresses.set-default');

    // Smart List routes
    Route::prefix('smart-lists')->group(function () {
        Route::post('{smart_list}/meals', [SmartListMealController::class, 'store']);
        Route::delete('{smart_list}/meals/{meal}', [SmartListMealController::class, 'destroy']);
    });
    Route::apiResource('smart-lists', SmartListController::class);

    // Notification Settings
    Route::prefix('notification-settings')->group(function () {
        Route::get('/', [NotificationSettingsController::class, 'index']);
        Route::put('/', [NotificationSettingsController::class, 'update']);
        Route::put('/category/{category}', [NotificationSettingsController::class, 'updateCategory']);
    });

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/stats', [NotificationController::class, 'stats']);
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        Route::get('/recent', [NotificationController::class, 'recent']);
        Route::put('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/delete-multiple', [NotificationController::class, 'destroyMultiple']);
        Route::delete('/clear-all', [NotificationController::class, 'clearAll']);
        Route::get('/type/{type}', [NotificationController::class, 'byType']);
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/{id}/unread', [NotificationController::class, 'markAsUnread']);
    });
    Route::apiResource('notifications', NotificationController::class)->only(['show', 'destroy']);

    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::delete('/clear', [CartClearController::class, 'destroy']);
    });
    Route::apiResource('cart', CartController::class)->only(['index', 'store', 'update', 'destroy'])->singleton();

    // Favorites routes
    Route::apiResource('favorites', FavoriteController::class)->only(['index', 'store', 'show', 'destroy']);

    // Chatbot routes
    Route::get('chatbot/suggestions', [ChatbotSuggestionController::class, 'index']);
    Route::apiResource('chatbot', ChatbotController::class)->only(['index', 'store']);

    // Reviews (Store & Index)
    Route::apiResource('reviews', ReviewController::class);

    // Stripe
    Route::get('/cards', [StripeController::class, 'listCards']);
    Route::post('/setup-intent', [StripeController::class, 'createSetupIntent']);
    Route::post('/charge-card', [StripeController::class, 'chargeSavedCard']);
    Route::delete('/cards/{id}', [StripeController::class, 'deleteCard']);

    // Order routes
    Route::apiResource('orders/tracking', OrderTrackingController::class)->only(['index']);
    Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show']);

    // Payment routes
    Route::prefix('payments')->group(function () {
        Route::post('/stripe/checkout-session', [StripeCheckoutController::class, 'store']);
        Route::get('/stripe/verify-session/{session_id}', [StripeCheckoutController::class, 'verifySession']);
    });
    Route::apiResource('payments', PaymentController::class)->only(['index', 'show']);

    // Dashboard & Loyalty
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/loyalty', [LoyaltyController::class, 'index']);

    // Help & Support
    Route::apiResource('support/report', SupportController::class)->only(['store']);

    // Frequency Meals
    Route::apiResource('frequency', FrequencyMealController::class)->only(['index']);
});

// App Settings (Partially Public)
Route::prefix('settings')->group(function () {
    Route::get('/', [SettingController::class, 'index']);
    Route::get('/language', [UserAppSettingsController::class, 'showLanguage']);
    Route::put('/language', [UserAppSettingsController::class, 'updateLanguage'])->middleware('auth:sanctum');
    Route::get('/appearance', [UserAppSettingsController::class, 'showAppearance']);
    Route::put('/appearance', [UserAppSettingsController::class, 'updateAppearance'])->middleware('auth:sanctum');
});

// Meals routes
Route::prefix('meals')->group(function () {
    Route::get('/today', [TodayDealController::class, 'index']);
    Route::get('/hot', [HotMealController::class, 'index']);
    Route::get('/recommendations', [RecommendedMealController::class, 'index']);
    Route::get('/brands', [MealBrandController::class, 'index']);
    Route::get('/new', [NewProductController::class, 'index']);
    Route::get('/best-sellers', [BestSellerController::class, 'index']);
    Route::get('/slider', [SliderMealController::class, 'index']);
});
Route::apiResource('meals', MealController::class)->only(['index', 'show']);

// Categories & Subcategories
Route::prefix('categories')->group(function () {
    Route::get('{category}/meals', [CategoryMealController::class, 'index']);
});
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

Route::prefix('subcategories')->group(function () {
    Route::get('{subcategory}/meals', [SubcategoryMealController::class, 'index']);
});
Route::apiResource('subcategories', SubcategoryController::class)->only(['index', 'show']);

// Other Public Routes
Route::get('special-notes', [SpecialNoteController::class, 'index']);
Route::prefix('offers')->group(function () {
    Route::get('/', [OfferController::class, 'index']);
    Route::get('/featured', [OfferController::class, 'featured']);
    Route::get('/validate', [OfferController::class, 'validateOffer']);
    Route::get('/{code}', [OfferController::class, 'showByCode']);
});

Route::get('/faqs', [FaqController::class, 'index']);
Route::prefix('pages')->group(function () {
    Route::get('/', [StaticPageController::class, 'index']);
    Route::get('/important', [StaticPageController::class, 'importantPages']);
    Route::get('/slug/{slug}', [StaticPageController::class, 'showBySlug']);
});

// Contact
Route::prefix('contact')->group(function () {
    Route::get('stats', [ContactStatsController::class, 'index'])->middleware('auth:sanctum');
});
Route::apiResource('contact', ContactController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

// Reviews Stats
Route::get('meals/{meal}/reviews/stats', [ReviewStatsController::class, 'show']);

// Health check route
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now(),
    ]);
});
