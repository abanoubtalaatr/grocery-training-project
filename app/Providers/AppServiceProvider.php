<?php

namespace App\Providers;

use App\Models\Meal;
use App\Observers\MealObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{

public function boot(): void
{
    Paginator::useBootstrapFive();
}
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
    //  */
    // public function boot(): void
    // {
    //     Meal::observe(MealObserver::class);
    // }
}
