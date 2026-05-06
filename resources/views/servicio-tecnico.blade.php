@extends('layouts.app')

@section('title', 'Servicio Técnico - Hamilton Beach Paraguay')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
        <a href="/" class="hover:text-brand transition">Inicio</a>
        <span class="mx-2">›</span>
        <a href="/centro-ayuda" class="hover:text-brand transition">Centro de ayuda</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-medium">Servicio Técnico</span>
    </div>
</div>

<!-- Hero -->
<div class="bg-brand text-white py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 mb-5">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold mb-3">Servicio Técnico Oficial</h1>
        <p class="text-lg text-brand-muted max-w-2xl mx-auto">
            Contamos con técnicos capacitados por Hamilton Beach para garantizar el correcto funcionamiento de tus electrodomésticos. Reparaciones con repuestos originales y garantía de servicio.
        </p>
    </div>
</div>

<!-- Contenido principal -->
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

            <p>Contamos con <strong>técnicos capacitados y certificados por Hamilton Beach</strong> para garantizar el correcto funcionamiento de tus electrodomésticos, utilizando únicamente repuestos originales de la marca.</p>

            <h2>¿Qué incluye nuestro servicio?</h2>
            <ul>
                <li><strong>Diagnóstico gratuito</strong> — evaluamos el estado de tu equipo sin costo.</li>
                <li><strong>Repuestos originales Hamilton Beach</strong> — calidad y durabilidad garantizada.</li>
                <li><strong>Garantía de reparación de 90 días</strong> sobre mano de obra y repuestos utilizados.</li>
                <li>Atención personalizada por técnicos autorizados por la marca.</li>
            </ul>

            <h2>¿Cómo solicitar el servicio?</h2>
            <ol>
                <li>Contactanos por WhatsApp o teléfono describiendo el problema y el modelo de tu equipo.</li>
                <li>Nuestro equipo técnico evalúa el equipo y te informa el presupuesto sin compromiso.</li>
                <li>Una vez aprobado, realizamos la reparación con repuestos originales Hamilton Beach.</li>
                <li>Retirás tu equipo funcionando con garantía de 90 días sobre la reparación realizada.</li>
            </ol>
        </div>

        <!-- CTA unificado -->
        @include('partials.cta-ayuda')

    </div>
</div>

@endsection
