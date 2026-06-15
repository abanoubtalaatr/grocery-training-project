<?php

namespace App\Actions\Profile;

use App\Exceptions\BusinessException;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteProfileImageAction
{
    use AsAction;

    /**
     * Handle deleting a user profile image.
     *
     * @throws BusinessException
     */
    public function handle(User $user): void
    {
        if (!$user->profile_image) {
            throw new BusinessException('No profile image to delete', 404);
        }

        // Delete image from storage
        if (Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Update user
        $user->update(['profile_image' => null]);
    }
}
