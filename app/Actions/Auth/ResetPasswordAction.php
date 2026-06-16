<?php

namespace App\Actions\Auth;

use App\Services\AuthService;

class ResetPasswordAction
{
    public function __construct(protected AuthService $authService) {}

    public function __invoke(string $identifier, string $otp, string $password): void
    {
        $this->authService->resetPassword($identifier, $otp, $password);
    }
}
