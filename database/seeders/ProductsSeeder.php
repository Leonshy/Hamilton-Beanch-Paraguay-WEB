<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        $catCafeteras  = Category::where('slug', 'cafeteras')->first();
        $catTostadoras = Category::where('slug', 'tostadoras')->first();
        $catPavas      = Category::where('slug', 'pavas')->first();
        $catMolinillos = Category::where('slug', 'molinillos')->first();

        $products = [
            [
                'slug'        => 'cafetera-espresso-retro-black',
                'title'       => 'Cafetera Espresso Retro Black',
                'subtitle'    => 'Cafetera de estilo retro en acabado negro mate. Tecnología de extracción óptima para una taza con sabor rico y aromático. Capacidad para 10 tazas, fácil de usar y limpiar, con piezas desmontables compatibles con lavavajillas.',
                'content'     => '<p>Descubrí la <strong>Cafetera Retro Black</strong>, el complemento perfecto para los amantes del café que buscan un toque de estilo vintage en su cocina.</p><h2>Características principales</h2><ul><li><strong>Diseño retro en negro mate</strong> con líneas clásicas que agregan sofisticación a tu cocina.</li><li><strong>Capacidad para 10 tazas</strong> (depósito de 1.2 L), ideal para toda la familia.</li><li><strong>Tecnología de extracción óptima</strong> para una taza con sabor rico y aromático.</li><li>Piezas desmontables <strong>compatibles con lavavajillas</strong> para una limpieza sin esfuerzo.</li></ul>',
                'price'       => 850000,
                'category_id' => $catCafeteras?->id,
                'status'      => 'published',
                'is_featured' => true,
                'order'       => 1,
                'specifications' => [
                    ['label' => 'Modelo',     'value' => 'Retro Black – 40730'],
                    ['label' => 'Color',      'value' => 'Negro mate'],
                    ['label' => 'Capacidad',  'value' => 'Hasta 10 tazas (1.2 L)'],
                    ['label' => 'Material',   'value' => 'Acero inoxidable y plástico ABS'],
                    ['label' => 'Voltaje',    'value' => '220 V / 50 Hz'],
                ],
            ],
            [
                'slug'        => 'cafetera-home-barista-7-in-1',
                'title'       => 'Cafetera Home Barista 7-in-1',
                'subtitle'    => 'La experiencia de un barista profesional en tu hogar. 7 funciones en un solo equipo para preparar todo tipo de bebidas de café.',
                'content'     => '<p>La <strong>Cafetera Home Barista 7-in-1</strong> te permite preparar espresso, cappuccino, latte y más desde la comodidad de tu hogar con resultados profesionales.</p><h2>Características principales</h2><ul><li><strong>7 funciones</strong>: espresso, americano, cappuccino, latte, café negro, café con leche y bebida caliente.</li><li>Pantalla digital con controles intuitivos.</li><li>Depósito de agua extraíble de 1.5 L.</li><li>Vaporizador de leche integrado.</li></ul>',
                'price'       => 1200000,
                'category_id' => $catCafeteras?->id,
                'status'      => 'published',
                'is_featured' => true,
                'order'       => 2,
                'specifications' => [
                    ['label' => 'Modelo',    'value' => 'Home Barista 7-in-1'],
                    ['label' => 'Funciones', 'value' => '7 modos de preparación'],
                    ['label' => 'Capacidad', 'value' => '1.5 L'],
                    ['label' => 'Voltaje',   'value' => '220 V / 50 Hz'],
                ],
            ],
            [
                'slug'        => 'tostadora-toaster-silver',
                'title'       => 'Tostadora Toaster Silver',
                'subtitle'    => 'Tostadora de 2 ranuras en acabado plateado. Control de dorado ajustable en 7 niveles, bandeja recogemigas desmontable.',
                'content'     => '<p>La <strong>Tostadora Toaster Silver</strong> combina diseño elegante con funcionalidad práctica. Perfecta para el desayuno diario.</p><h2>Características principales</h2><ul><li>2 ranuras extra anchas para todo tipo de pan.</li><li>7 niveles de dorado ajustable.</li><li>Función descongelación y recalentamiento.</li><li>Bandeja recogemigas desmontable y lavable.</li></ul>',
                'price'       => 320000,
                'category_id' => $catTostadoras?->id,
                'status'      => 'published',
                'is_featured' => true,
                'order'       => 3,
                'specifications' => [
                    ['label' => 'Modelo',  'value' => 'Toaster Silver'],
                    ['label' => 'Color',   'value' => 'Plateado'],
                    ['label' => 'Ranuras', 'value' => '2 ranuras extra anchas'],
                    ['label' => 'Niveles', 'value' => '7 niveles de dorado'],
                    ['label' => 'Voltaje', 'value' => '220 V / 50 Hz'],
                ],
            ],
            [
                'slug'        => 'pava-electrica-cool-touch',
                'title'       => 'Pava Eléctrica Cool-Touch',
                'subtitle'    => 'Pava eléctrica con tecnología Cool-Touch que mantiene el exterior frío al tacto incluso cuando el agua está caliente.',
                'content'     => '<p>La <strong>Pava Cool-Touch</strong> es ideal para familias con niños gracias a su tecnología de doble pared que mantiene el exterior frío al tacto.</p><h2>Características principales</h2><ul><li>Tecnología Cool-Touch: exterior frío al tacto.</li><li>Capacidad de 1.7 litros.</li><li>Apagado automático y protección contra ebullición en seco.</li><li>Filtro anti-cal desmontable.</li></ul>',
                'price'       => 280000,
                'category_id' => $catPavas?->id,
                'status'      => 'published',
                'is_featured' => true,
                'order'       => 4,
                'specifications' => [
                    ['label' => 'Modelo',    'value' => 'Cool-Touch'],
                    ['label' => 'Capacidad', 'value' => '1.7 L'],
                    ['label' => 'Potencia',  'value' => '2200 W'],
                    ['label' => 'Voltaje',   'value' => '220 V / 50 Hz'],
                ],
            ],
            [
                'slug'        => 'pava-electrica-double-wall',
                'title'       => 'Pava Eléctrica Double-Wall',
                'subtitle'    => 'Pava de doble pared que conserva la temperatura del agua caliente por más tiempo. Diseño moderno en acero inoxidable.',
                'content'     => '<p>La <strong>Pava Double-Wall</strong> mantiene el agua caliente por más tiempo gracias a su construcción de doble pared en acero inoxidable.</p><h2>Características principales</h2><ul><li>Doble pared para mayor conservación del calor.</li><li>Capacidad de 1.5 litros.</li><li>Base giratoria 360°.</li><li>Apagado automático al hervir.</li></ul>',
                'price'       => 350000,
                'category_id' => $catPavas?->id,
                'status'      => 'published',
                'is_featured' => true,
                'order'       => 5,
                'specifications' => [
                    ['label' => 'Modelo',    'value' => 'Double-Wall'],
                    ['label' => 'Capacidad', 'value' => '1.5 L'],
                    ['label' => 'Material',  'value' => 'Acero inoxidable doble pared'],
                    ['label' => 'Voltaje',   'value' => '220 V / 50 Hz'],
                ],
            ],
            [
                'slug'        => 'molinillo-de-cafe-profesional',
                'title'       => 'Molinillo de Café Profesional',
                'subtitle'    => 'Molinillo de café con cuchillas de acero inoxidable. 18 ajustes de grosor para espresso, cafetera y prensa francesa.',
                'content'     => '<p>El <strong>Molinillo de Café Profesional</strong> te permite moler los granos al nivel perfecto para cada método de preparación.</p><h2>Características principales</h2><ul><li>18 ajustes de grosor: de fino (espresso) a grueso (prensa francesa).</li><li>Cuchillas de acero inoxidable de larga duración.</li><li>Capacidad para 100 g de café.</li><li>Temporizador integrado para porciones exactas.</li></ul>',
                'price'       => 450000,
                'category_id' => $catMolinillos?->id,
                'status'      => 'published',
                'is_featured' => true,
                'order'       => 6,
                'specifications' => [
                    ['label' => 'Modelo',   'value' => 'Molinillo Profesional'],
                    ['label' => 'Ajustes',  'value' => '18 niveles de grosor'],
                    ['label' => 'Capacidad','value' => '100 g'],
                    ['label' => 'Potencia', 'value' => '150 W'],
                    ['label' => 'Voltaje',  'value' => '220 V / 50 Hz'],
                ],
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['user_id' => $admin?->id])
            );
        }
    }
}
