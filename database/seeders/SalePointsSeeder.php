<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalePointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $points = [
            ['name' => 'Stock Center',       'url' => 'https://www.stockcenter.com.py'],
            ['name' => 'Electro Sur',        'url' => 'https://www.electrosur.com.py'],
            ['name' => 'Casa Rica',          'url' => 'https://www.casarica.com.py'],
            ['name' => 'Frávega Paraguay',   'url' => 'https://www.fravega.com.py'],
            ['name' => 'Hipermaxi',          'url' => 'https://www.hipermaxi.com.py'],
            ['name' => 'SuperSeis',          'url' => 'https://www.superseis.com.py'],
            ['name' => 'Punto Fácil',        'url' => 'https://www.puntofacil.com.py'],
            ['name' => 'ABC Digital',        'url' => 'https://www.abcdigital.com.py'],
            ['name' => 'Electrohogar',       'url' => 'https://www.electrohogar.com.py'],
            ['name' => 'Tekno Store',        'url' => 'https://www.teknostore.com.py'],
        ];

        foreach ($points as $i => $point) {
            \App\Models\SalePoint::create([
                'name'      => $point['name'],
                'url'       => $point['url'],
                'is_active' => true,
                'order'     => $i + 1,
            ]);
        }
    }
}
