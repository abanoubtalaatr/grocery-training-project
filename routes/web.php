<?php

use App\Http\Controllers\StripePaymentCallbackController;
use App\Http\Controllers\WebChatController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MealController;
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
Route::prefix("auth")->group(function(){

    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('auth.login');
    }
);

Route::prefix('admins')->middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'dashboard'])->name('admins.dashboard');
    Route::delete('/categories/mass-destroy', [CategoryController::class, 'massDestroy'])->name('admins.categories.mass-destroy');
    Route::resource('categories', CategoryController::class)->names('admins.categories');
    Route::delete('/orders/mass-destroy', [OrderController::class, 'massDestroy'])->name('admins.orders.mass-destroy');
    Route::resource('orders', OrderController::class)->names('admins.orders');
    Route::delete('/meals/mass-destroy', [MealController::class, 'massDestroy'])->name('admins.meals.mass-destroy');
    Route::resource('meals', MealController::class)->names('admins.meals');
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

// مسار تجريبي تعليمي لتجربة نظام الطوابير (Queue) وتتابع المهام (Jobs Chain)
Route::get('/test-welcome-chain', function () {
    // 1. إنشاء مستخدم تجريبي أو جلبه إذا كان موجوداً مسبقاً
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'student@example.com'],
        [
            'username' => 'student_learner_' . time(),
            'firstname' => 'أحمد',
            'lastname' => 'التعليمي',
            'password' => bcrypt('password123'),
            'email_verified' => true,
            'agree_terms' => true,
        ]
    );

    // 2. تشغيل المهام بتسلسل متتالي (Chaining) باستخدام Bus::chain
    // هذا يعني أن GenerateWelcomePdfJob ستعمل أولاً، وعند نجاحها ستعمل SendWelcomeEmailJob مباشرة
    \Illuminate\Support\Facades\Bus::chain([
        new \App\Jobs\GenerateWelcomePdfJob($user),
        new \App\Jobs\SendWelcomeEmailJob($user),
    ])->dispatch();

    return response()->json([
        'status' => 'success',
        'message' => 'تمت إضافة الـ Jobs إلى الـ Queue بنجاح بنظام التسلسل (Chain)!',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
        'next_steps' => [
            '1' => 'قم بفتح جدول jobs في قاعدة البيانات للتأكد من تخزين الـ Job الأولى فيه.',
            '2' => 'قم بتشغيل الأمر: php artisan queue:work لتنفيذ الـ Jobs.',
            '3' => 'تأكد من إنشاء ملف الـ PDF في المجلد: storage/app/public.',
            '4' => 'تأكد من ظهور الإيميل الترحيبي مع المرفق داخل الملف: storage/logs/laravel.log.'
        ]
    ]);
});

