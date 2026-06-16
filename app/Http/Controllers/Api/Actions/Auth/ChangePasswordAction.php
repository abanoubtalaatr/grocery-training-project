<?php

namespace App\Http\Controllers\Api\Actions\Auth;

use App\Models\User;

class ChangePasswordAction
{
    public function handle(User $user, string $password): void
    {
        $user->update([
            'password' => $password,
        ]);
    }
}
