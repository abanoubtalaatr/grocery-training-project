<?php

namespace App\Actions\Auth;

use App\Services\AuthService;

class SendForgotPasswordOtpAction
{
    public function __construct(protected AuthService $authService) {}

    public function __invoke(string $identifier): void
    {
        $this->authService->forgotPassword($identifier);
    }
}
