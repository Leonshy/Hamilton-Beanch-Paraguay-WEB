<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        $pages = [
            [
                'slug'    => 'servicio-tecnico',
                'section' => 'servicio-tecnico',
                'title'   => 'Servicio Técnico',
                'subtitle'=> 'Reparaciones con repuestos originales',
                'icon'    => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z||M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                'content' => '<p>Contamos con <strong>técnicos capacitados y certificados por Hamilton Beach</strong> para garantizar el correcto funcionamiento de tus electrodomésticos, utilizando únicamente repuestos originales de la marca.</p><h2>¿Qué incluye nuestro servicio?</h2><ul><li><strong>Diagnóstico gratuito</strong> — evaluamos el estado de tu equipo sin costo.</li><li><strong>Repuestos originales Hamilton Beach</strong> — calidad y durabilidad garantizada.</li><li><strong>Garantía de reparación de 90 días</strong> sobre mano de obra y repuestos utilizados.</li><li>Atención personalizada por técnicos autorizados por la marca.</li></ul><h2>¿Cómo solicitar el servicio?</h2><ol><li>Contactanos por WhatsApp o teléfono describiendo el problema y el modelo de tu equipo.</li><li>Nuestro equipo técnico evalúa el equipo y te informa el presupuesto sin compromiso.</li><li>Una vez aprobado, realizamos la reparación con repuestos originales Hamilton Beach.</li><li>Retirás tu equipo funcionando con garantía de 90 días sobre la reparación realizada.</li></ol>',
                'status'  => 'published',
                'order'   => 1,
            ],
            [
                'slug'    => 'manuales-de-producto',
                'section' => 'manuales',
                'title'   => 'Manuales',
                'subtitle'=> 'Guías de uso de todos los productos',
                'icon'    => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                'content' => '<p>Cada producto Hamilton Beach incluye un <strong>manual de uso</strong> con información detallada sobre su funcionamiento, recomendaciones de seguridad y consejos de mantenimiento.</p><h2>¿Cómo encontrar el manual de tu producto?</h2><ol><li>Ingresá a la sección <a href="/productos">Productos</a> y encontrá el modelo de tu electrodoméstico Hamilton Beach.</li><li>Hacé clic en el producto para ver toda la información, características y especificaciones técnicas.</li><li>Al final de la descripción encontrarás el enlace para descargar el manual de uso en formato PDF.</li></ol><h2>¿Por qué es importante leer el manual?</h2><ul><li>Asegura el uso correcto y seguro del electrodoméstico.</li><li>Prolonga la vida útil de tu producto.</li><li>Contiene advertencias de seguridad importantes.</li><li>Es requerido para validar la garantía en caso de reclamo.</li><li>Incluye guías de limpieza y mantenimiento.</li><li>Describe todas las funciones y modos de operación.</li></ul><p><strong>Recomendación:</strong> Conservá el manual impreso en un lugar seguro junto con la factura de compra y la póliza de garantía.</p>',
                'status'  => 'published',
                'order'   => 2,
            ],
            [
                'slug'    => 'garantia-de-producto',
                'section' => 'garantia',
                'title'   => 'Garantías',
                'subtitle'=> 'Póliza y condiciones de garantía oficial',
                'icon'    => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                'content' => '<p><strong>Importante:</strong> Conservá este documento junto con la factura de compra. En caso de pérdida de la póliza, el distribuidor podrá emitir una nueva contra presentación de la factura original.</p><h2>¿Qué cubre la garantía?</h2><ul><li>Defectos de fabricación en materiales y mano de obra.</li><li>Fallas en el funcionamiento normal del equipo.</li><li>Reemplazo de partes y componentes defectuosos.</li><li>Mano de obra dentro de la red de servicio autorizado.</li></ul><h2>¿Qué NO cubre la garantía?</h2><ul><li>Daños por uso incorrecto o negligencia.</li><li>Daños por voltaje inadecuado o sobretensión.</li><li>Reparaciones realizadas por técnicos no autorizados.</li><li>Daños estéticos (rayones, golpes, decoloración).</li><li>Productos con número de serie alterado o removido.</li><li>Desgaste natural por uso normal.</li></ul><h2>¿Cómo hacer un reclamo de garantía?</h2><ol><li>Contactanos por teléfono, WhatsApp o formulario de contacto indicando el modelo del equipo y la falla.</li><li>Tené a mano la factura de compra y la póliza de garantía para agilizar el proceso.</li><li>Nuestro equipo coordinará la revisión del equipo. El plazo máximo de reparación bajo garantía es de 30 días hábiles.</li><li>Una vez reparado, recibirás tu equipo en perfectas condiciones sin costo adicional.</li></ol>',
                'status'  => 'published',
                'order'   => 3,
            ],
            [
                'slug'    => 'preguntas-frecuentes',
                'section' => 'preguntas-frecuentes',
                'title'   => 'Preguntas Frecuentes',
                'subtitle'=> 'Respuestas a las consultas más comunes',
                'icon'    => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'status'  => 'published',
                'order'   => 4,
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['user_id' => $admin?->id])
            );
        }
    }
}
