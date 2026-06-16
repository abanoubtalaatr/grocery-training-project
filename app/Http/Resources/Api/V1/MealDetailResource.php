<?php


namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\ReviewResource;
use App\Http\Resources\MealResource;
use Illuminate\Http\Request;

class MealDetailResource extends MealResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'includes' => $this->includes,
            'how_to_use' => $this->how_to_use,
            'expiry_date' => $this->expiry_date,
            'days_until_expiry' => $this->daysUntilExpiry(),
            'is_expired' => $this->isExpired(),
            'is_available' => $this->is_available,
            'updated_at' => $this->updated_at,
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ]);
    }
}