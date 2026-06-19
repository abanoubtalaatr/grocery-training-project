<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class UserService
{

    public function paginate(
    Request $request,
    int $perPage = 10
    )
    {
        return User::query()
            ->filter($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
    public function create(array $data): User
    {
        $data['password'] = Hash::make(
            $data['password']
        );

        return User::create($data);
    }

    public function update(
        User $user,
        array $data
    ): bool {

        if (empty($data['password'])) {

            unset($data['password']);

        } else {

            $data['password'] = Hash::make(
                $data['password']
            );

        }

        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}