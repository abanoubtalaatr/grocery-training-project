<?php

namespace App\Actions\Auth;

use App\Models\User;

class ChangePasswordAction
{
    public function __invoke(User $user, string $password): void
    {
        $user->update([
            'password' => $password,
        ]);
    }
}
