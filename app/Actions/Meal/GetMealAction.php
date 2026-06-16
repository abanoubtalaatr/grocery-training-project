<?php

namespace App\Actions\Meal;

use App\Repositories\MealRepository;
use App\Traits\FormatsMeal;

class GetMealAction
{
    use FormatsMeal;

    public function __construct(private readonly MealRepository $mealRepository) {}

    public function __invoke(string $id): array
    {
        $meal = $this->mealRepository->findByIdWithDetails($id);
        
        $data = $this->formatMealForApi($meal);
        
        // Add detailed fields for the show endpoint
        $data['includes'] = $meal->includes;
        $data['how_to_use'] = $meal->how_to_use;
        $data['expiry_date'] = $meal->expiry_date;
        $data['days_until_expiry'] = $meal->daysUntilExpiry();
        $data['is_expired'] = $meal->isExpired();
        $data['updated_at'] = $meal->updated_at;

        if ($meal->relationLoaded('reviews')) {
            $data['reviews'] = $meal->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => $review->relationLoaded('user') && $review->user ? [
                        'id' => $review->user->id,
                        'name' => $review->user->full_name ?? $review->user->username ?? 'User',
                    ] : null,
                    'rating' => (int) $review->rating,
                    'comment' => $review->comment,
                    'images' => $review->images ?? [],
                    'created_at' => $review->created_at?->toIso8601String(),
                ];
            })->values();
        }

        return $data;
    }
}
