<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'username'            => $this->username,
            'firstname'           => $this->firstname,
            'lastname'            => $this->lastname,
            'full_name'           => $this->full_name,
            'gender'              => $this->gender,
            'birthday'            => $this->birthday,
            'email'               => $this->email,
            'phone'               => $this->phone,
            'country_code'        => $this->country_code,
            'profile_image_url'   => $this->profile_image_url,
            'email_verified'      => $this->email_verified,
            'phone_verified'      => $this->phone_verified,
            'email_verified_at'   => $this->email_verified_at,
            'phone_verified_at'   => $this->phone_verified_at,
            'agree_terms'         => $this->agree_terms,
            'is_active'           => $this->is_active,
            'is_admin'            => $this->is_admin,
            'loyalty_points'      => $this->loyalty_points,
            'store_credits'       => $this->store_credits,
            'preferred_languages' => $this->preferred_languages,
            'app_language'        => $this->app_language,
            'app_theme'           => $this->app_theme,
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
        ];
    }
}