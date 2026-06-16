<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'username' => 'admin',
                'firstname' => 'System',
                'lastname' => 'Admin',
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'is_admin' => true,
                'is_active' => true,
                'email_verified' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}