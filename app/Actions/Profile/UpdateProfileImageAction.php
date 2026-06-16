<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateProfileImageAction
{
    public function __invoke(User $user, UploadedFile $image): array
    {
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $image->store('profile-images', 'public');

        $user->update(['profile_image' => $path]);

        return [
            'profile_image' => $user->profile_image,
            'profile_image_url' => $user->profile_image_url,
        ];
    }
}
