@extends('layouts.app')

@section('title', 'Garantía de Producto - Hamilton Beach Paraguay')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
        <a href="/" class="hover:text-brand transition">Inicio</a>
        <span class="mx-2">›</span>
        <a href="/centro-ayuda" class="hover:text-brand transition">Centro de ayuda</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-medium">Garantía de Producto</span>
    </div>
</div>

<!-- Hero -->
<div class="bg-brand text-white py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 mb-5">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold mb-3">Póliza de Garantía Hamilton Beach</h1>
        <p class="text-lg text-brand-muted max-w-2xl mx-auto">
            Todos los productos Hamilton Beach vendidos en Paraguay cuentan con garantía oficial de 1 año contra defectos de fabricación.
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

            <p><strong>Importante:</strong> Conservá este documento junto con la factura de compra. En caso de pérdida de la póliza, el distribuidor podrá emitir una nueva contra presentación de la factura original.</p>

            <h2>¿Qué cubre la garantía?</h2>
            <ul>
                <li>Defectos de fabricación en materiales y mano de obra.</li>
                <li>Fallas en el funcionamiento normal del equipo.</li>
                <li>Reemplazo de partes y componentes defectuosos.</li>
                <li>Mano de obra dentro de la red de servicio autorizado.</li>
            </ul>

            <h2>¿Qué NO cubre la garantía?</h2>
            <ul>
                <li>Daños por uso incorrecto o negligencia.</li>
                <li>Daños por voltaje inadecuado o sobretensión.</li>
                <li>Reparaciones realizadas por técnicos no autorizados.</li>
                <li>Daños estéticos (rayones, golpes, decoloración).</li>
                <li>Productos con número de serie alterado o removido.</li>
                <li>Desgaste natural por uso normal.</li>
            </ul>

            <h2>¿Cómo hacer un reclamo de garantía?</h2>
            <ol>
                <li>Contactanos por teléfono, WhatsApp o formulario de contacto indicando el modelo del equipo y la falla.</li>
                <li>Tené a mano la factura de compra y la póliza de garantía para agilizar el proceso.</li>
                <li>Nuestro equipo coordinará la revisión del equipo. El plazo máximo de reparación bajo garantía es de 30 días hábiles.</li>
                <li>Una vez reparado, recibirás tu equipo en perfectas condiciones sin costo adicional.</li>
            </ol>
        </div>

        <!-- CTA unificado -->
        @include('partials.cta-ayuda')

    </div>
</div>

@endsection
