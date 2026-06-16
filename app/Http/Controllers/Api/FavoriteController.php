<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Meal;
use App\Traits\ResponseApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    use ResponseApi;
    /**
     * Get all user's favorite meals
     */
    public function index(Request $request): JsonResponse
    {
      
            $user = $request->user();
            
            $favorites = $user->favorites()
                ->with(['meal.category', 'meal.subcategory'])
                ->latest()
                ->get()
                ->map(function ($favorite) {
                    $meal = $favorite->meal;
                    return [
                        'id' => $meal->id,
                        'title' => $meal->title,
                        'slug' => $meal->slug,
                        'description' => $meal->description,
                        'image_url' => $meal->image_url,
                        'offer_title' => $meal->offer_title,
                        
                        // Pricing
                        ...$meal->getApiPriceAttributes(),
                        'has_offer' => $meal->hasOffer(),
                        
                        // Rating & Details
                        'rating' => (float) $meal->rating,
                        'rating_count' => (int) $meal->rating_count,
                        'size' => $meal->size,
                        'brand' => $meal->brand,
                        
                        // Stock & Availability
                        'stock_quantity' => $meal->stock_quantity,
                        'in_stock' => $meal->isInStock(),
                        'is_available' => $meal->is_available,
                        'is_featured' => $meal->is_featured,
                        
                        // Category & Subcategory
                        'category' => [
                            'id' => $meal->category->id,
                            'name' => $meal->category->name,
                            'slug' => $meal->category->slug,
                        ],
                        'subcategory' => $meal->subcategory ? [
                            'id' => $meal->subcategory->id,
                            'name' => $meal->subcategory->name,
                            'slug' => $meal->subcategory->slug,
                        ] : null,
                        
                        'is_favorited' => true,
                        'favorited_at' => $favorite->created_at,
                    ];
                });

            return $this->success( 'Favorites retrieved successfully', $favorites);
       
    }

    /**
     * Toggle favorite status for a meal
     */
    public function toggle(Request $request, Meal $meal): JsonResponse
    {
        try {
            $user = $request->user();
            DB::beginTransaction();

            $favorite = $user->favorites()->where('meal_id', $meal->id)->first();

            if ($favorite) {
                // Remove from favorites
                $favorite->delete();
                $isFavorited = false;
                $message = 'Removed from favorites';
            } else {
                // Add to favorites
                $user->favorites()->create([
                    'meal_id' => $meal->id,
                ]);
                $isFavorited = true;
                $message = 'Added to favorites';
            }

            DB::commit();

            return $this->success( $message,
                [
                    'meal_id' => $meal->id,
                    'is_favorited' => $isFavorited,
                ],
            );

        } 
        catch (\Exception $e) {
            DB::rollBack();
            return $this->failed('Failed to toggle favorite', $e->getMessage(), 500);
        }
    }

    /**
     * Check if a meal is favorited
     */
    public function check(Request $request, Meal $meal): JsonResponse
    {
        try {
            
            $isFavorited = $request->user->favorites()->where('meal_id', $meal->id)->exists();

            return $this->success(data: ['meal_id' => $meal->id,'is_favorited' => $isFavorited,]);
        }  catch (\Exception $e) {
            return $this->failed( 'Failed to check favorite status', $e->getMessage(),500);
        }
    }

    /**
     * Remove meal from favorites
     */
    public function remove(Request $request, Meal $meal): JsonResponse
    {
     
            $deleted = $request->user->favorites()->where('meal_id', $meal->id)->delete();

            if ($deleted) {
                return $this->success('Removed from favorites',['meal_id' => $meal->id,'is_favorited' => false, ],);
              
            } else {
                return $this->failed('Meal was not in favorites', status:404);
            }
        
    }
}
