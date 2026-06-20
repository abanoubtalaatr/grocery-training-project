<?php
namespace App\Providers;

use App\Models\Meal;
use App\Observers\MealObserver;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        Meal::observe(MealObserver::class);

        
        Response::macro('success', function ($data = null, string $message = 'Success', int $status = 200, array $meta = []) {
            return response()->json(array_merge([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], $meta), $status);
        });

        Response::macro('error',
         function (string $message, int $status = 400 ) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        });
    }
}
