<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository
{
    public function getOrCreateDemoUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'webchat@grocery.demo'],
            [
                'firstname' => 'Web',
                'lastname' => 'Chat Demo',
                'username' => 'webchat_demo',
                'password' => Hash::make(Str::random(32)),
            ]
        );
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function existsWithUsername(string $username): bool
    {
        return User::withTrashed()->where('username', $username)->exists();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }
}
