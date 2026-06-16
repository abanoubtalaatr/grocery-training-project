<?php

namespace App\Actions\Auth;

use App\Services\AuthService;

class LoginAction
{
    public function __construct(protected AuthService $authService) {}

    public function __invoke(string $login, string $password): array
    {
        return $this->authService->login($login, $password);
    }
}
