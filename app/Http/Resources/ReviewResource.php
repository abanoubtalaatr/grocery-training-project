<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => (int) $this->rating,
            'comment' => $this->comment,
            'images' => $this->images ?? [],
            'is_approved' => (bool) $this->is_approved,
            'created_at' => $this->created_at instanceof \DateTimeInterface 
                ? $this->created_at->format('Y-m-d H:i:s') 
                : $this->created_at,
            'updated_at' => $this->updated_at instanceof \DateTimeInterface 
                ? $this->updated_at->format('Y-m-d H:i:s') 
                : $this->updated_at,
            
            // User information
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name ?? $this->user->username ?? 'User',
                'avatar' => $this->user->avatar ?? null,
            ] : null,
            
            // Meal information
            'meal' => $this->meal ? [
                'id' => $this->meal->id,
                'name' => $this->meal->name,
                'slug' => $this->meal->slug,
                'image' => $this->meal->image,
            ] : null,
            
            // Links for API
            'links' => [
                'self' => route('reviews.show', $this->id),
                'meal' => route('meals.show', $this->meal_id),
            ]
        ];
    }
}
