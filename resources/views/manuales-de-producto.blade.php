@extends('layouts.app')

@section('title', 'Manuales de Producto - Hamilton Beach Paraguay')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
        <a href="/" class="hover:text-brand transition">Inicio</a>
        <span class="mx-2">›</span>
        <a href="/centro-ayuda" class="hover:text-brand transition">Centro de ayuda</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-medium">Manuales de Producto</span>
    </div>
</div>

<!-- Hero -->
<div class="bg-brand text-white py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 mb-5">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold mb-3">Manuales de Uso Hamilton Beach</h1>
        <p class="text-lg text-brand-muted max-w-2xl mx-auto">
            Cada producto Hamilton Beach incluye un manual de uso con información detallada sobre su funcionamiento, recomendaciones de seguridad y consejos de mantenimiento.
        </p>
    </div>
</div>

<!-- Contenido -->
<div class="bg-gray-50 py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Texto enriquecido desde el backend: {!! $pagina->contenido !!} --}}
        <div class="mb-10
                    prose prose-gray max-w-none
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

            <p>Cada producto Hamilton Beach incluye un <strong>manual de uso</strong> con información detallada sobre su funcionamiento, recomendaciones de seguridad y consejos de mantenimiento.</p>

            <h2>¿Cómo encontrar el manual de tu producto?</h2>
            <ol>
                <li>Ingresá a la sección <a href="/productos">Productos</a> y encontrá el modelo de tu electrodoméstico Hamilton Beach.</li>
                <li>Hacé clic en el producto para ver toda la información, características y especificaciones técnicas.</li>
                <li>Al final de la descripción encontrarás el enlace para descargar el manual de uso en formato PDF.</li>
            </ol>

            <h2>¿Por qué es importante leer el manual?</h2>
            <ul>
                <li>Asegura el uso correcto y seguro del electrodoméstico.</li>
                <li>Prolonga la vida útil de tu producto.</li>
                <li>Contiene advertencias de seguridad importantes.</li>
                <li>Es requerido para validar la garantía en caso de reclamo.</li>
                <li>Incluye guías de limpieza y mantenimiento.</li>
                <li>Describe todas las funciones y modos de operación.</li>
            </ul>

            <p><strong>Recomendación:</strong> Conservá el manual impreso en un lugar seguro junto con la factura de compra y la póliza de garantía.</p>
        </div>

        <!-- CTA unificado -->
        @include('partials.cta-ayuda')

    </div>
</div>

@endsection
