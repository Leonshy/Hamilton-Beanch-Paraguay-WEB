<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannersSeeder extends Seeder
{
    public function run(): void
    {
        Banner::updateOrCreate(
            ['title' => 'Calidad Hamilton Beach'],
            [
                'title'       => 'Calidad Hamilton Beach',
                'subtitle'    => 'para tu hogar',
                'description' => 'Cafeteras, tostadoras, pavas eléctricas y molinillos de café con respaldo y garantía oficial en Paraguay.',
                'cta_text'    => 'Ver catálogo',
                'cta_url'     => '/productos',
                'position'    => 'home',
                'order'       => 1,
                'is_active'   => true,
            ]
        );
    }
}
