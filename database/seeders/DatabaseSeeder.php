<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndUsersSeeder::class,
            SiteSettingsSeeder::class,
            CategoriesSeeder::class,
            ProductsSeeder::class,
            PagesSeeder::class,
            FaqsSeeder::class,
            BannersSeeder::class,
        ]);
    }
}
