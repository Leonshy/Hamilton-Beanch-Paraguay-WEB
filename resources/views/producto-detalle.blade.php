@extends('layouts.app')

@section('title', 'Cafetera Espresso Retro Black - Hamilton Beach Paraguay')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
        <a href="/" class="hover:text-brand transition">Inicio</a>
        <span class="mx-2">›</span>
        <a href="/productos" class="hover:text-brand transition">Productos</a>
        <span class="mx-2">›</span>
        <a href="/productos?categoria=cafeteras" class="hover:text-brand transition">Cafeteras</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-medium">Cafetera Espresso Retro Black</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Sección principal: galería + info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-14">

        <!-- Galería de imágenes -->
        <div>
            <div class="bg-gray-50 rounded-2xl h-96 flex items-center justify-center mb-3 border border-gray-200 overflow-hidden" id="main-gallery-img">
                <img src="/images/products/cafetera-retro-black-1.webp"
                     alt="Cafetera Espresso Retro Black"
                     class="w-full h-full object-contain p-6">
            </div>
            <div class="grid grid-cols-4 gap-2">
                @php
                    $gallery = [
                        '/images/products/cafetera-retro-black-1.webp',
                        '/images/products/cafetera-retro-black-2.webp',
                        '/images/products/cafetera-retro-black-3.webp',
                        '/images/products/cafetera-retro-black-4.webp',
                    ];
                @endphp
                @foreach($gallery as $img)
                <button onclick="document.querySelector('#main-gallery-img img').src='{{ $img }}'"
                        class="bg-gray-50 rounded-xl h-20 border-2 border-transparent hover:border-brand transition overflow-hidden">
                    <img src="{{ $img }}" alt="Galería" class="w-full h-full object-contain p-2">
                </button>
                @endforeach
            </div>
        </div>

        <!-- Información del producto -->
        <div>
            <!-- Encabezado -->
            <div class="mb-5">
                <span class="inline-block text-xs font-semibold text-brand uppercase tracking-wider mb-2">Cafeteras</span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2 leading-tight">
                    Cafetera Espresso Retro Black
                </h1>
                <p class="text-xl font-bold text-gray-800 mb-3">Precio sugerido ≈ Gs. 850.000</p>
                <div class="inline-flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-100 px-3 py-1.5 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Disponible en puntos de venta
                </div>
            </div>

            <!-- Descripción corta -->
            <p class="text-gray-600 leading-relaxed mb-6">
                Cafetera de estilo retro en acabado negro mate. Tecnología de extracción óptima para una taza con sabor rico y aromático. Capacidad para 10 tazas, fácil de usar y limpiar, con piezas desmontables compatibles con lavavajillas.
            </p>

            <!-- Puntos de venta -->
            <div class="mb-6">
                <h2 class="text-base font-bold text-gray-900 mb-3">¿Dónde comprar?</h2>
                <p class="text-sm text-gray-500 mb-4">Encontrá este producto en nuestros puntos de venta autorizados:</p>
                <div class="space-y-3">
                    <a href="#" target="_blank"
                       class="flex items-center justify-between w-full bg-gray-800 hover:bg-gray-900 text-white px-5 py-3.5 rounded-xl font-semibold transition group">
                        <span>Punto de Venta A</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    <a href="#" target="_blank"
                       class="flex items-center justify-between w-full bg-gray-800 hover:bg-gray-900 text-white px-5 py-3.5 rounded-xl font-semibold transition group">
                        <span>Punto de Venta B</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    <a href="#" target="_blank"
                       class="flex items-center justify-between w-full bg-gray-800 hover:bg-gray-900 text-white px-5 py-3.5 rounded-xl font-semibold transition group">
                        <span>Punto de Venta C</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- WhatsApp -->
            <a href="https://wa.me/595911234567" target="_blank"
               class="w-full flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-semibold transition mb-5">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Consultar por WhatsApp
            </a>

            <!-- Compartir -->
            <div class="flex items-center gap-4 text-sm text-gray-400">
                <span>Compartir:</span>
                <a href="#" class="hover:text-blue-600 transition">Facebook</a>
                <a href="#" class="hover:text-sky-500 transition">Twitter</a>
                <a href="#" class="hover:text-green-500 transition">WhatsApp</a>
            </div>
        </div>
    </div>

    <!-- Descripción + Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-14">

        <!-- Descripción enriquecida -->
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">Descripción del producto</h2>

            {{-- Zona de texto enriquecido proveniente del backend --}}
            <div class="prose prose-gray max-w-none
                        prose-headings:font-bold prose-headings:text-gray-900
                        prose-h2:text-xl prose-h2:mt-8 prose-h2:mb-3
                        prose-h3:text-base prose-h3:mt-6 prose-h3:mb-2
                        prose-p:text-gray-600 prose-p:leading-relaxed prose-p:mb-4
                        prose-ul:text-gray-600 prose-ul:space-y-1 prose-ul:pl-5 prose-ul:list-disc
                        prose-ol:text-gray-600 prose-ol:space-y-1 prose-ol:pl-5 prose-ol:list-decimal
                        prose-li:leading-relaxed
                        prose-strong:text-gray-900 prose-strong:font-semibold
                        prose-a:text-brand prose-a:underline hover:prose-a:text-brand-dark
                        prose-table:w-full prose-table:border-collapse
                        prose-th:bg-gray-50 prose-th:text-left prose-th:px-4 prose-th:py-2 prose-th:text-sm prose-th:font-semibold prose-th:text-gray-700 prose-th:border prose-th:border-gray-200
                        prose-td:px-4 prose-td:py-2 prose-td:text-sm prose-td:text-gray-600 prose-td:border prose-td:border-gray-200">

                {{-- Ejemplo representativo — se reemplaza por {!! $producto->descripcion !!} al conectar el backend --}}
                <p>Descubrí la <strong>Cafetera Retro Black</strong>, el complemento perfecto para los amantes del café que buscan un toque de estilo vintage en su cocina. Con tecnología de vanguardia y un diseño atemporal, esta cafetera no solo es funcional, sino que eleva la estética de cualquier espacio.</p>

                <h2>Características principales</h2>
                <ul>
                    <li><strong>Diseño retro en negro mate</strong> con líneas clásicas que agregan sofisticación a tu cocina.</li>
                    <li><strong>Capacidad para 10 tazas</strong> (depósito de 1.2 L), ideal para toda la familia.</li>
                    <li><strong>Tecnología de extracción óptima</strong> para una taza con sabor rico y aromático.</li>
                    <li>Piezas desmontables <strong>compatibles con lavavajillas</strong> para una limpieza sin esfuerzo.</li>
                </ul>

                <h2>Especificaciones técnicas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Especificación</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Modelo</td><td>Retro Black – 40730</td></tr>
                        <tr><td>Color</td><td>Negro mate</td></tr>
                        <tr><td>Capacidad</td><td>Hasta 10 tazas (1.2 L)</td></tr>
                        <tr><td>Material</td><td>Acero inoxidable y plástico ABS</td></tr>
                        <tr><td>Voltaje</td><td>220 V / 50 Hz</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Manual -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center gap-3 mt-8">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-900">Manual de uso disponible</p>
                    <a href="#" class="text-blue-600 hover:underline text-sm">Descargar PDF →</a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Productos relacionados -->
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-4">Productos relacionados</h3>
                <div class="space-y-3">
                    @php
                        $relacionados = [
                            ['id' => 2, 'nombre' => 'Cafetera Home Barista 7-in-1', 'categoria' => 'Cafeteras', 'imagen' => '/images/products/cafetera-home-barista.webp'],
                            ['id' => 4, 'nombre' => 'Pava Eléctrica Cool-Touch', 'categoria' => 'Pavas', 'imagen' => '/images/products/pava-cool-touch.webp'],
                            ['id' => 6, 'nombre' => 'Molinillo de Café Profesional', 'categoria' => 'Molinillos', 'imagen' => '/images/products/pava-digital.webp'],
                        ];
                    @endphp
                    @foreach($relacionados as $rel)
                    <a href="/productos/{{ $rel['id'] }}"
                       class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl hover:border-brand-muted hover:shadow-sm transition group">
                        <div class="w-14 h-14 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <img src="{{ $rel['imagen'] }}" alt="{{ $rel['nombre'] }}" class="w-full h-full object-contain p-1">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400">{{ $rel['categoria'] }}</p>
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-brand transition leading-snug line-clamp-2">{{ $rel['nombre'] }}</p>
                        </div>
                        <svg class="w-4 h-4 text-brand flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ del producto -->
    <div class="bg-white border border-gray-200 rounded-xl p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Preguntas frecuentes sobre este producto</h2>
        <div class="divide-y divide-gray-100">
            @php
                $faqs = [
                    ['q' => '¿Cuál es la capacidad máxima de la cafetera?', 'a' => 'La capacidad máxima es de 10 tazas de café. El depósito de agua tiene una capacidad de 1.2 litros.'],
                    ['q' => '¿Incluye filtros?', 'a' => 'Sí, la cafetera viene con filtros permanentes reutilizables. También podés usar filtros de papel desechables compatibles.'],
                    ['q' => '¿Qué cubre la garantía de este producto?', 'a' => 'La garantía de 1 año cubre defectos de fabricación y problemas de funcionamiento. No cubre daños por mal uso, caídas o desgaste natural.'],
                ];
            @endphp
            @foreach($faqs as $faq)
            <div class="py-5">
                <button class="w-full text-left font-semibold text-gray-800 hover:text-brand flex justify-between items-start gap-4 transition" onclick="toggleFaq(this)">
                    <span>{{ $faq['q'] }}</span>
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden mt-3">
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

<script>
function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const icon = btn.querySelector('svg');
    answer.classList.toggle('hidden');
    icon.style.transform = answer.classList.contains('hidden') ? '' : 'rotate(180deg)';
}
</script>

@endsection
