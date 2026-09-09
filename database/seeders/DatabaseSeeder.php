<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndAdminSeeder::class,
            GuideAndVehicleSeeder::class,
            ActivitySeeder::class,
            PromotionSeeder::class,
            ExpenseCategorySeeder::class,
            CmsSeeder::class,
        ]);
    }
}