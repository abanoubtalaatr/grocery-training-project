<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\EmailController;
use App\Http\Controllers\Api\V1\MealController;
use App\Http\Controllers\Api\V1\SubcategoryController;
use Illuminate\Support\Facades\Route;




Route::get('/meals', [MealController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/subcategories', [SubcategoryController::class, 'index']);

Route::get('/send-email', [EmailController::class, 'sendEmail']);