<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProfileImageAction
{
    use AsAction;

    /**
     * Handle updating user profile image.
     */
    public function handle(User $user, $imageFile): array
    {
        // Delete old image if exists
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Store new image
        $path = $imageFile->store('profile-images', 'public');

        // Update user
        $user->update(['profile_image' => $path]);

        return [
            'profile_image' => $user->profile_image,
            'profile_image_url' => $user->profile_image_url,
        ];
    }
}
