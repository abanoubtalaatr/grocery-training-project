<?php

namespace App\Http\Controllers\Api\Actions\Auth;

use App\Services\AuthService;

class ForgotPasswordAction
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function handle(string $identifier): void
    {
        $this->authService->forgotPassword($identifier);
    }
}
