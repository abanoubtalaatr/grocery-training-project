<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        User::factory()->count(50)->create();

        Category::factory()->count(12)->create();

        Order::factory()->count(100)->create();
    }
}
