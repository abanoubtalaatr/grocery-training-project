<?php

namespace App\Http\Controllers\Api\Actions\Auth;

use App\Services\AuthService;
use Illuminate\Support\Arr;

class RegisterAction
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function handle(array $validated): array
    {
        return $this->authService->register($validated);
    }
}
