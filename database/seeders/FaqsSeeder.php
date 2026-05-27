<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqsSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => '¿Dónde puedo comprar los productos Hamilton Beach en Paraguay?',
                'answer'   => 'Los productos Hamilton Beach están disponibles en nuestros puntos de venta autorizados en todo el país. En cada ficha de producto encontrarás los enlaces a los puntos de venta donde podés adquirirlo. También podés consultarnos directamente por WhatsApp para saber el punto más cercano a tu ubicación.',
                'category' => 'general',
                'order'    => 1,
                'is_active'=> true,
            ],
            [
                'question' => '¿Qué garantía tienen los productos?',
                'answer'   => 'Todos los productos Hamilton Beach vendidos a través de distribuidores autorizados en Paraguay cuentan con garantía oficial de 1 año que cubre defectos de fabricación y problemas de funcionamiento. La garantía no cubre daños por mal uso, accidentes, voltaje inadecuado ni desgaste natural. Consultá la póliza completa en nuestra sección de Garantía.',
                'category' => 'garantia',
                'order'    => 2,
                'is_active'=> true,
            ],
            [
                'question' => '¿Cómo valido la garantía de mi producto?',
                'answer'   => 'Para validar la garantía necesitás conservar la factura de compra emitida por el punto de venta autorizado y la póliza de garantía. Si perdiste la póliza, podemos emitir una nueva contra la presentación de la factura original. Contactanos por WhatsApp o formulario de contacto.',
                'category' => 'garantia',
                'order'    => 3,
                'is_active'=> true,
            ],
            [
                'question' => '¿Tienen servicio técnico oficial en Paraguay?',
                'answer'   => 'Sí, contamos con servicio técnico oficial con técnicos capacitados por Hamilton Beach. Realizamos diagnóstico gratuito, reparaciones con repuestos originales y ofrecemos 90 días de garantía sobre la reparación. Para solicitar el servicio contactanos por WhatsApp o completá el formulario de contacto.',
                'category' => 'servicio',
                'order'    => 4,
                'is_active'=> true,
            ],
            [
                'question' => '¿Cómo obtengo el manual de uso de mi producto?',
                'answer'   => 'Los manuales de uso están disponibles en la ficha de cada producto en nuestro catálogo. Ingresá a la sección Productos, encontrá tu modelo y descargá el PDF desde el enlace al final de la descripción. También podés visitar nuestra sección de Manuales de Producto para obtener más información.',
                'category' => 'productos',
                'order'    => 5,
                'is_active'=> true,
            ],
            [
                'question' => '¿Qué hago si mi producto presenta una falla?',
                'answer'   => 'Si tu producto presenta una falla, seguí estos pasos: 1) Revisá el manual de uso por si es un problema de configuración o uso. 2) Si el equipo está dentro del período de garantía, contactanos con la factura y póliza de garantía. 3) Nuestro equipo técnico te asesorará sobre el proceso de reparación sin costo. Contactanos por WhatsApp o formulario.',
                'category' => 'servicio',
                'order'    => 6,
                'is_active'=> true,
            ],
            [
                'question' => '¿Venden repuestos y accesorios?',
                'answer'   => 'Sí, contamos con repuestos originales Hamilton Beach. Para consultar disponibilidad del repuesto que necesitás, contactanos por WhatsApp indicando el modelo de tu equipo y la pieza requerida.',
                'category' => 'productos',
                'order'    => 7,
                'is_active'=> true,
            ],
            [
                'question' => '¿Tienen descuentos para empresas o compras al por mayor?',
                'answer'   => 'Sí, ofrecemos condiciones especiales para empresas, instituciones y revendedores. Para recibir una cotización personalizada, contactanos por email a info@hamiltonbeach.com.py indicando los productos de interés y las cantidades.',
                'category' => 'general',
                'order'    => 8,
                'is_active'=> true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
