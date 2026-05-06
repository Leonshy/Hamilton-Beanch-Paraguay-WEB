@extends('layouts.app')

@section('title', 'Preguntas Frecuentes - Hamilton Beach Paraguay')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
        <a href="/" class="hover:text-brand transition">Inicio</a>
        <span class="mx-2">›</span>
        <a href="/centro-ayuda" class="hover:text-brand transition">Centro de ayuda</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-medium">Preguntas frecuentes</span>
    </div>
</div>

<!-- Hero -->
<div class="bg-brand text-white py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 mb-5">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold mb-3">Preguntas frecuentes</h1>
        <p class="text-lg text-brand-muted">Encontrá respuestas a las consultas más comunes sobre nuestros productos y servicios.</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    @php
        $faqs = [
            [
                'titulo' => '¿Dónde puedo comprar los productos Hamilton Beach en Paraguay?',
                'respuesta' => 'Los productos Hamilton Beach están disponibles en nuestros puntos de venta autorizados en todo el país. En cada ficha de producto encontrarás los enlaces a los puntos de venta donde podés adquirirlo. También podés consultarnos directamente por WhatsApp para saber el punto más cercano a tu ubicación.',
            ],
            [
                'titulo' => '¿Qué garantía tienen los productos?',
                'respuesta' => 'Todos los productos Hamilton Beach vendidos a través de distribuidores autorizados en Paraguay cuentan con garantía oficial de 1 año que cubre defectos de fabricación y problemas de funcionamiento. La garantía no cubre daños por mal uso, accidentes, voltaje inadecuado ni desgaste natural. Consultá la póliza completa en nuestra sección de Garantía.',
            ],
            [
                'titulo' => '¿Cómo valido la garantía de mi producto?',
                'respuesta' => 'Para validar la garantía necesitás conservar la factura de compra emitida por el punto de venta autorizado y la póliza de garantía. Si perdiste la póliza, podemos emitir una nueva contra la presentación de la factura original. Contactanos por WhatsApp o formulario de contacto.',
            ],
            [
                'titulo' => '¿Tienen servicio técnico oficial en Paraguay?',
                'respuesta' => 'Sí, contamos con servicio técnico oficial con técnicos capacitados por Hamilton Beach. Realizamos diagnóstico gratuito, reparaciones con repuestos originales y ofrecemos 90 días de garantía sobre la reparación. Para solicitar el servicio contactanos por WhatsApp o completá el formulario de contacto.',
            ],
            [
                'titulo' => '¿Cómo obtengo el manual de uso de mi producto?',
                'respuesta' => 'Los manuales de uso están disponibles en la ficha de cada producto en nuestro catálogo. Ingresá a la sección Productos, encontrá tu modelo y descargá el PDF desde el enlace al final de la descripción. También podés visitar nuestra sección de Manuales de Producto para obtener más información.',
            ],
            [
                'titulo' => '¿Qué hago si mi producto presenta una falla?',
                'respuesta' => 'Si tu producto presenta una falla, seguí estos pasos: 1) Revisá el manual de uso por si es un problema de configuración o uso. 2) Si el equipo está dentro del período de garantía, contactanos con la factura y póliza de garantía. 3) Nuestro equipo técnico te asesorará sobre el proceso de reparación sin costo. Contactanos por WhatsApp o formulario.',
            ],
            [
                'titulo' => '¿Venden repuestos y accesorios?',
                'respuesta' => 'Sí, contamos con repuestos originales Hamilton Beach. Para consultar disponibilidad del repuesto que necesitás, contactanos por WhatsApp indicando el modelo de tu equipo y la pieza requerida.',
            ],
            [
                'titulo' => '¿Tienen descuentos para empresas o compras al por mayor?',
                'respuesta' => 'Sí, ofrecemos condiciones especiales para empresas, instituciones y revendedores. Para recibir una cotización personalizada, contactanos por email a info@hamiltonbeach.com.py indicando los productos de interés y las cantidades.',
            ],
        ];
    @endphp

    <div class="space-y-2">
        @foreach($faqs as $faq)
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <button
                class="w-full px-6 py-5 text-left flex justify-between items-center gap-4 hover:bg-gray-50 transition"
                onclick="toggleFaq(this)">
                <span class="font-semibold text-gray-800 text-sm leading-snug">{{ $faq['titulo'] }}</span>
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="faq-content hidden px-6 pb-5">
                <div class="border-t border-gray-100 pt-4">
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $faq['respuesta'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- CTA unificado -->
    <div class="mt-12">
        @include('partials.cta-ayuda')
    </div>

</div>

<script>
function toggleFaq(btn) {
    const content = btn.nextElementSibling;
    const icon = btn.querySelector('svg');
    const isOpen = !content.classList.contains('hidden');

    // Cerrar todos los abiertos
    document.querySelectorAll('.faq-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[onclick="toggleFaq(this)"] svg').forEach(el => {
        el.style.transform = '';
    });

    // Abrir el clickeado (si estaba cerrado)
    if (!isOpen) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>

@endsection
