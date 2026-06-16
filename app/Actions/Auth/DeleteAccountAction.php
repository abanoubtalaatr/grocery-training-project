<?php

namespace App\Actions\Auth;

use App\Services\AuthService;
use App\Models\User;

class DeleteAccountAction
{
    public function __construct(protected AuthService $authService) {}

    public function __invoke(User $user): void
    {
        $this->authService->deleteAccount($user);
    }
}
