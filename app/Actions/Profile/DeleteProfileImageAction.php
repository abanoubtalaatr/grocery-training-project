<?php

namespace App\Actions\Profile;

use App\Services\ProfileService;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class DeleteProfileImageAction
{
    public function __construct(protected ProfileService $service)
    {
    }

    public function execute(UserContract $user): UserContract
    {
        return $this->service->deleteProfileImage($user);
    }
}
