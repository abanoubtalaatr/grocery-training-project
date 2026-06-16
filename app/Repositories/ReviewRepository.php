<?php

namespace App\Repositories;

use App\Models\Review;
use App\Models\Meal;

class ReviewRepository
{
    public function getFiltered(array $filters, int $perPage)
    {
        $query = Review::query()->with(['user', 'meal'])->latest();
        
        if (isset($filters['meal_id'])) {
            $query->where('meal_id', $filters['meal_id']);
        }
        
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
        
        if (isset($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }
        
        if (!isset($filters['approved_only']) || $filters['approved_only']) {
            $query->approved();
        }
        
        if (isset($filters['min_rating'])) {
            $query->where('rating', '>=', $filters['min_rating']);
        }
        
        return $query->paginate($perPage);
    }

    public function hasUserReviewed(int $userId, int $mealId): bool
    {
        return Review::hasUserReviewed($userId, $mealId);
    }

    public function create(array $data)
    {
        return Review::create($data);
    }

    public function findById(int $id)
    {
        return Review::findOrFail($id);
    }

    public function findByIdWithRelations(int $id)
    {
        return Review::with(['user', 'meal'])->findOrFail($id);
    }

    public function getForMeal(int $mealId, int $perPage)
    {
        return Review::with('user')
            ->where('meal_id', $mealId)
            ->approved()
            ->latest()
            ->paginate($perPage);
    }

    public function getForUser(int $userId, int $perPage)
    {
        return Review::with('meal')
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function getStats(int $mealId)
    {
        return Review::where('meal_id', $mealId)
            ->approved()
            ->selectRaw('
                COUNT(*) as total_reviews,
                AVG(rating) as average_rating,
                COUNT(CASE WHEN rating = 5 THEN 1 END) as five_star,
                COUNT(CASE WHEN rating = 4 THEN 1 END) as four_star,
                COUNT(CASE WHEN rating = 3 THEN 1 END) as three_star,
                COUNT(CASE WHEN rating = 2 THEN 1 END) as two_star,
                COUNT(CASE WHEN rating = 1 THEN 1 END) as one_star
            ')
            ->first();
    }

    public function search($request)
    {
        return Review::approved()->search($request)->get();
    }

    public function getAverageRating(int $mealId)
    {
        return Review::getAverageRating($mealId);
    }

    public function getTotalReviews(int $mealId)
    {
        return Review::getTotalReviews($mealId);
    }

    public function findMeal(int $mealId)
    {
        return Meal::findOrFail($mealId);
    }
}
