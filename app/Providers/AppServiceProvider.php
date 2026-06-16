<?php

namespace App\Providers;

use App\Models\Meal;
use App\Models\Order;
use App\Observers\MealObserver;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Filament\Http\Responses\Auth\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Meal::observe(MealObserver::class);
        Order::observe(OrderObserver::class);
    }
}
