<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cafeteras', 'slug' => 'cafeteras', 'type' => 'product', 'order' => 1,
                'icon_type' => 'svg', 'icon' => 'coffee-maker.svg',
            ],
            [
                'name' => 'Tostadoras', 'slug' => 'tostadoras', 'type' => 'product', 'order' => 2,
                'icon_type' => 'svg', 'icon' => 'toaster.svg',
            ],
            [
                'name' => 'Pavas Eléctricas', 'slug' => 'pavas', 'type' => 'product', 'order' => 3,
                'icon_type' => 'svg', 'icon' => 'water-heater.svg',
            ],
            [
                'name' => 'Molinillos', 'slug' => 'molinillos', 'type' => 'product', 'order' => 4,
                'icon_type' => 'svg', 'icon' => 'blender.svg',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], array_merge($cat, ['is_active' => true]));
        }
    }
}
