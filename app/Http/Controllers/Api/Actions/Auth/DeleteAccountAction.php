<?php

namespace App\Http\Controllers\Api\Actions\Auth;

use App\Services\AuthService;
use App\Models\User;

class DeleteAccountAction
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function handle(User $user): void
    {
        $this->authService->deleteAccount($user);
    }
}
