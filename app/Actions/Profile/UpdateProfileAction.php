<?php

namespace App\Actions\Profile;

use App\Services\ProfileService;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class UpdateProfileAction
{
    public function __construct(protected ProfileService $service)
    {
    }

    public function execute(UserContract $user, array $data): UserContract
    {
        return $this->service->updateProfile($user, $data);
    }
}
