<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create/update admin user for learning/demo environment
        User::updateOrCreate([
            'username' => 'admin',
        ], [
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'is_active' => true,
            'email_verified' => true,
            'phone_verified' => false,
            'agree_terms' => true,
            'email_verified_at' => now(),
            'country_code' => '+20',
        ]);
    }
}
