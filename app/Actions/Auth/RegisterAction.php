<?php

namespace App\Actions\Auth;

use App\Services\AuthService;

class RegisterAction
{
    public function __construct(protected AuthService $authService) {}

    public function __invoke(array $data): array
    {
        return $this->authService->register($data);
    }
}
