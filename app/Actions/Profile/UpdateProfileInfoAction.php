<?php

namespace App\Actions\Profile;

use App\Models\User;

class UpdateProfileInfoAction
{
    public function __invoke(User $user, array $data): array
    {
        if (isset($data['preferred_languages'])) {
            $data['preferred_languages'] = $data['preferred_languages'] ?? [];
        }

        $filteredData = array_filter($data, function ($value, $key) {
            if ($key === 'preferred_languages') {
                return true;
            }
            return $value !== null && $value !== '';
        }, ARRAY_FILTER_USE_BOTH);

        if (!empty($filteredData)) {
            $user->update($filteredData);
        }

        return [
            'id' => $user->id,
            'username' => $user->username,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'full_name' => $user->full_name,
            'gender' => $user->gender,
            'birthday' => $user->birthday?->format('Y-m-d'),
            'email' => $user->email,
            'phone' => $user->phone,
            'country_code' => $user->country_code,
            'preferred_languages' => $user->preferred_languages ?? [],
            'profile_image_url' => $user->profile_image_url,
            'updated_at' => $user->updated_at,
        ];
    }
}
