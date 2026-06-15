<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'type_label' => $this->getTypeLabel(),
            'discount_value' => $this->discount_value,
            'minimum_purchase' => $this->minimum_purchase,
            'start_date' => $this->start_date instanceof \DateTimeInterface 
                ? $this->start_date->format('Y-m-d') 
                : $this->start_date,
            'end_date' => $this->end_date instanceof \DateTimeInterface 
                ? $this->end_date->format('Y-m-d') 
                : $this->end_date,
            'usage_limit' => $this->usage_limit,
            'used_count' => $this->used_count,
            'remaining_uses' => $this->usage_limit ? $this->usage_limit - $this->used_count : null,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'is_valid' => $this->isValid(),
            'days_remaining' => max(0, now()->diffInDays($this->end_date, false)),
            'created_at' => $this->created_at instanceof \DateTimeInterface 
                ? $this->created_at->format('Y-m-d H:i:s') 
                : $this->created_at,
            'updated_at' => $this->updated_at instanceof \DateTimeInterface 
                ? $this->updated_at->format('Y-m-d H:i:s') 
                : $this->updated_at,
        ];
    }

    protected function getTypeLabel(): string
    {
        return match($this->type) {
            'percentage' => 'Percentage Discount',
            'fixed' => 'Fixed Amount',
            'buy_one_get_one' => 'Buy One Get One',
            'free_shipping' => 'Free Shipping',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }
}
