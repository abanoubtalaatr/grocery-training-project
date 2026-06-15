<?php

namespace App\Actions\Profile;

use App\Services\ProfileService;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class UpdateProfileImageAction
{
    public function __construct(protected ProfileService $service)
    {
    }

    public function execute(UserContract $user, $image): UserContract
    {
        return $this->service->updateProfileImage($user, $image);
    }
}
