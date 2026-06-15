<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function updateProfile(UserContract $user, array $data): UserContract
    {
        if (isset($data['preferred_languages'])) {
            $data['preferred_languages'] = $data['preferred_languages'] ?? [];
        }

        $data = array_filter($data, function ($value, $key) {
            if ($key === 'preferred_languages') {
                return true;
            }

            return $value !== null && $value !== '';
        }, ARRAY_FILTER_USE_BOTH);

        $user->update($data);

        return $user->fresh();
    }

    public function updateProfileImage(UserContract $user, $image): UserContract
    {
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $image->store('profile-images', 'public');

        $user->update(['profile_image' => $path]);

        return $user->fresh();
    }

    public function deleteProfileImage(UserContract $user): UserContract
    {
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->update(['profile_image' => null]);

        return $user->fresh();
    }
}
