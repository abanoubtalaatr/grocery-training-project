<?php

namespace App\Http\Controllers\Api\Actions\Auth;

use App\Services\AuthService;

class ResetPasswordAction
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function handle(string $identifier, string $otp, string $password): void
    {
        $this->authService->resetPassword($identifier, $otp, $password);
    }
}
