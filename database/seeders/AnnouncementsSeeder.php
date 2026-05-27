<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['text' => 'DISTRIBUIDOR OFICIAL HAMILTON BEACH® EN PARAGUAY', 'order' => 1],
            ['text' => 'GARANTÍA 1 AÑO CON SERVICIO TÉCNICO OFICIAL',       'order' => 2],
        ];

        foreach ($items as $item) {
            Announcement::firstOrCreate(['text' => $item['text']], array_merge($item, ['is_active' => true]));
        }
    }
}
