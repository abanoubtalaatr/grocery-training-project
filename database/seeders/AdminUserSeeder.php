<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@grocery.com'],
            [
                'username'       => 'admin',
                'firstname'      => 'Super',
                'lastname'       => 'Admin',
                'password'       => bcrypt('password'),
                'is_admin'       => true,
                'is_active'      => true,
                'email_verified' => true,
            ]
        );
    }
}
