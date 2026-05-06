@extends('layouts.app')

@section('title', 'Centro de Ayuda - Hamilton Beach Paraguay')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
        <a href="/" class="hover:text-brand transition">Inicio</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-medium">Centro de ayuda</span>
    </div>
</div>

<!-- Hero -->
<div class="bg-brand text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold mb-3">Centro de ayuda</h1>
        <p class="text-lg text-brand-muted">¿En qué podemos ayudarte hoy?</p>
    </div>
</div>

<!-- Hub de categorías -->
<div class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-16">

            <a href="/preguntas-frecuentes"
               class="group flex flex-col items-center text-center p-8 bg-white border border-gray-200 rounded-2xl hover:border-brand-muted hover:shadow-md transition">
                <div class="w-16 h-16 bg-brand-light group-hover:bg-brand-light rounded-2xl flex items-center justify-center mb-5 transition">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-brand transition mb-1">Preguntas Frecuentes</h3>
                <p class="text-gray-500 text-sm">Respuestas a las consultas más comunes</p>
            </a>

            <a href="/servicio-tecnico"
               class="group flex flex-col items-center text-center p-8 bg-white border border-gray-200 rounded-2xl hover:border-brand-muted hover:shadow-md transition">
                <div class="w-16 h-16 bg-brand-light group-hover:bg-brand-light rounded-2xl flex items-center justify-center mb-5 transition">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-brand transition mb-1">Servicio Técnico</h3>
                <p class="text-gray-500 text-sm">Reparaciones con repuestos originales</p>
            </a>

            <a href="/manuales-de-producto"
               class="group flex flex-col items-center text-center p-8 bg-white border border-gray-200 rounded-2xl hover:border-brand-muted hover:shadow-md transition">
                <div class="w-16 h-16 bg-brand-light group-hover:bg-brand-light rounded-2xl flex items-center justify-center mb-5 transition">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-brand transition mb-1">Manuales</h3>
                <p class="text-gray-500 text-sm">Guías de uso de todos los productos</p>
            </a>

            <a href="/garantia-de-producto"
               class="group flex flex-col items-center text-center p-8 bg-white border border-gray-200 rounded-2xl hover:border-brand-muted hover:shadow-md transition">
                <div class="w-16 h-16 bg-brand-light group-hover:bg-brand-light rounded-2xl flex items-center justify-center mb-5 transition">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-brand transition mb-1">Garantías</h3>
                <p class="text-gray-500 text-sm">Póliza y condiciones de garantía oficial</p>
            </a>

        </div>

        <!-- CTA -->
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-10 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-3">¿No encontraste la respuesta que buscabas?</h2>
            <p class="text-gray-500 mb-7">Nuestro equipo está listo para ayudarte.</p>
            <a href="/contacto"
               class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white px-8 py-3 rounded-lg font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Obtener ayuda
            </a>
        </div>

    </div>
</div>

@endsection
