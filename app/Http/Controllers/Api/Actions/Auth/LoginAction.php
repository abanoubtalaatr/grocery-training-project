<?php

namespace App\Http\Controllers\Api\Actions\Auth;

use App\Services\AuthService;

class LoginAction
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function handle(string $login, string $password): array
    {
        return $this->authService->login($login, $password);
    }
}
