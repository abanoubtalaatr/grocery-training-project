<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request): array
    {
        $address = $this->resource;

        return [
            'id' => $address->id,
            'label' => $address->label,
            'full_name' => $address->full_name,
            'phone' => $address->phone,
            'country_code' => $address->country_code,
            'formatted_phone' => $address->formatted_phone,
            'street_address' => $address->street_address,
            'building_number' => $address->building_number,
            'floor' => $address->floor,
            'apartment' => $address->apartment,
            'landmark' => $address->landmark,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'country' => $address->country,
            'notes' => $address->notes,
            'is_default' => $address->is_default,
            'latitude' => $address->latitude,
            'longitude' => $address->longitude,
            'full_address' => $address->full_address,
            'created_at' => $address->created_at,
            'updated_at' => $address->updated_at,
        ];
    }
}
